<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicine;

class softDeleteIfZero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soft-delete-if-zero';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $medicines = Medicine::all();

        foreach($medicines as $medicine){
            if($medicine->quantitiy == 0){
                $medicine->delete();
            }
        }

    }
}
