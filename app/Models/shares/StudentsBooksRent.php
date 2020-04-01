<?php

namespace App\Models\shares;

use App\Common\Definition;
use App\Models\baseinfo\Student;
use App\Models\BaseModel;
use App\Models\config\Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StudentsBooksRent extends BaseModel
{
    use SoftDeletes;

    protected $table = 'students_books_rents';

    protected $fillable = [
        'private_book_id',
        'renter_id',
        'renter_name',
        'lender_id',
        'lender_name',
        'shared_book_id',
        'shared_book_name',
        'shared_book_cover',
        'statement',
        'rend_applied_at',
        'rend_canceled_at',
        'rend_allowed_at',
        'rend_rejected_at',
        'return_applied_at',
        'return_confirm_at',
        'cast_beans',
        'over_limit_days',
        'blacked'
    ];

    protected $casts = [
        'blacked' => 'boolean'
    ];

    // 借出者
    public function lender()
    {
        return $this->belongsTo(Student::class, 'lender_id', 'id');
    }

    // 借阅者
    public function renter()
    {
        return $this->belongsTo(Student::class, 'renter_id', 'id');
    }

    // 取消借阅申请
    public function cancelRentApplication()
    {
        $statement = $this->getAttribute('statement');
        if ($statement == Definition::SHARED_BOOK_RENT_STATE_APPLYING) {
            $this->update([
                'statement' => Definition::SHARED_BOOK_RENT_STATE_CANCELED,
                'rend_canceled_at' => Carbon::now(),
            ]);
            return true;
        }
        return false;
    }

    // 同意借阅
    public function grantRentApplication()
    {
        $statement = $this->getAttribute('statement');
        if ($statement == Definition::SHARED_BOOK_RENT_STATE_APPLYING) {
            $this->update([
                'statement' => Definition::SHARED_BOOK_RENT_STATE_RENTING,
                'rend_allowed_at' => Carbon::now(),
            ]);
            return true;
        }
        return false;
    }

    // 借书者发起图书归还申请
    public function commitReturnApplication()
    {
        $statement = $this->getAttribute('statement');
        if ($statement == Definition::SHARED_BOOK_RENT_STATE_RENTING) {
            DB::beginTransaction();
            try {
                $this->handleBeans();
            } catch (\Exception $exception) {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => $exception->getMessage()
                ];
            }
            $this->update([
                'statement' => Definition::SHARED_BOOK_RENT_STATE_RETURNING,
                'return_applied_at' => Carbon::now(),
            ]);

            DB::commit();
            return [
                'status' => true,
                'message' => '图书归还成功'
            ];
        }
        return [
            'status' => false,
            'message' => '图书归还失败'
        ];
    }

    // 确认图书已归还
    public function grantReturnApplication()
    {
        $statement = $this->getAttribute('statement');
        if ($statement == Definition::SHARED_BOOK_RENT_STATE_RETURNING) {
            $this->update([
                'statement' => Definition::SHARED_BOOK_RENT_STATE_RETURNED,
                'return_confirm_at' => Carbon::now(),
            ]);
            return true;
        }
        return false;
    }

    // 拒绝图书借阅申请
    public function rejectRentApplication()
    {
        $statement = $this->getAttribute('statement');
        if ($statement == Definition::SHARED_BOOK_RENT_STATE_APPLYING) {
            $this->update([
                'statement' => Definition::SHARED_BOOK_RENT_STATE_REJECTED,
                'rend_rejected_at' => Carbon::now(),
            ]);
            return true;
        }
        return false;
    }

    /**
     * @return array
     * @throws \Exception
     * @author marhone
     */
    private function handleBeans()
    {
        // checking
        $limitDays = Config::get(Definition::CONFIG_MODULE_RENT, 'limit_days', null);

        if ($limitDays > 0) {
            $blockDays = Config::get(Definition::CONFIG_MODULE_RENT, 'block_days', null);
            $cost = Config::get(Definition::CONFIG_MODULE_RENT, 'cost', null);
            $start = Carbon::parse($this->getAttribute('rend_allowed_at'));
            $limit = $start->addDays($limitDays);
            $now = Carbon::now()->toDateTimeString();
            $overLimitDays = $limit->diffInDays($now, false);

            if ($overLimitDays > $blockDays) {
                return [
                    'status' => false,
                    'message' => "已超过 {$blockDays} 天还书期限, 账户被锁定!"
                ];
            }

            if ($overLimitDays > 0) {
                $costBean = $cost * $overLimitDays;
                /** @var Student $user */
                $user = $this->renter;
                $totalBean = $user->getAttribute('total_beans');
                if ($totalBean -  $costBean > 0) {
                    $user->acquireBeanBy($costBean, Definition::BOOK_BEAN_CHANGE_BY_RENT);
                    $this->update([
                        'cast_beans' => $costBean,
                        'over_limit_days' => $overLimitDays
                    ]);
                } else {
                    throw new \Exception("超过 {$overLimitDays} 天还书, 应扣 {$costBean} 个书豆, 账户书豆不足, 请充值!");
                }
            }
        }
    }
}
