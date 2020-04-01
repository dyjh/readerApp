<?php

namespace App\Console\Commands;

use App\Imports\SchoolsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcel extends Command
{
    protected $signature = 'import:excel';

    protected $description = 'Laravel Excel importer';

    public function handle()
    {
        ini_set('memory_limit','520M');
        $this->output->title('Starting import');
      //  (new SchoolsImport)->queue('DW11.xlsx');
        Excel::queueImport(new SchoolsImport, 'public/DW3.xlsx');
      //  (new SchoolsImport)->withOutput($this->output)->import('DW11.xlsx');
        $this->output->success('Import successful');
    }
}
