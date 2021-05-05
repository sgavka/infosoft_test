<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Console\Command;

class AccrueDepositPercents extends Command
{
    const SLEEP_SECONDS = 60;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:accrue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accrue deposit percent every 1 minute.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        while (true) {
            $deposits = Deposit::allActive()->all();

            /** @var Deposit $deposit */
            foreach ($deposits as $deposit) {
                $deposit->processPayout();
            }

            sleep(self::SLEEP_SECONDS);
        }

        return 0;
    }
}
