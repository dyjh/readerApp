<?php

namespace App\Console\Commands\shares;

use App\Common\Definition;
use App\Models\baseinfo\Student;
use App\Models\config\Config;
use App\Models\shares\StudentsBooksRent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentBlacklistScanner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blacklist:scanner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查借书超过最长期限未还的用户并禁用';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $blockDays = Config::get(Definition::CONFIG_MODULE_RENT, 'block_days', '');
        $blacklist = Config::get(Definition::CONFIG_MODULE_RENT, 'blacklist', '');

        DB::beginTransaction();
        try {
            $now = new Carbon();
            if ($blacklist) {
                /** @var StudentsBooksRent[] $overtimes */
                $overtimes = StudentsBooksRent
                    ::with(['renter'])
                    ->whereRaw('date_add(rend_allowed_at, interval ? day) < now()', $blockDays)
                    ->where('statement', Definition::SHARED_BOOK_RENT_STATE_RENTING)
                    ->where('blacked', 0)
                    ->get();
                foreach ($overtimes as $rent) {
                    /** @var Student $student */
                    $student = $rent->renter;
                    $start = Carbon::parse($rent->getAttribute('rend_allowed_at'));
                    $limit = $start->addDays($blockDays);
                    $overLimitDays = $limit->diffInDays($now, false);
                    $student->blacklist(true);
                    $rent->update([
                        'over_limit_days' => $overLimitDays,
                        'blacked' => true
                    ]);
                }
                // SHOULD LOGGING
                dump($overtimes->count());
            }
            DB::commit();
        } catch (\Exception $exception) {
            Log::error("[{$this->name}] {$exception->getMessage()}");
            DB::rollback();
        }
    }
}
