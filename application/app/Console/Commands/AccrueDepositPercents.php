<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Console\Command;

class AccrueDepositPercents extends Command
{
    const MAX_ACCRUE_TIMES = 10;
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
            $deposits = Deposit::query()->where('active', true)->get()->all();
            $this->processDeposits($deposits);
            sleep(self::SLEEP_SECONDS);
        }

        return 0;
    }

    /**
     * @param array $deposits
     */
    private function processDeposits(array $deposits): void
    {
        /** @var Deposit $deposit */
        foreach ($deposits as $deposit) {
            $transaction = new Transaction();

            $transaction->user_id = $deposit->user_id;
            $transaction->wallet_id = $deposit->wallet_id;
            $transaction->deposit_id = $deposit->id;
            $transaction->amount = $deposit->invested * ($deposit->percent / 100);
            $wallet = $deposit->wallet;
            $wallet->balance += $transaction->amount;
            if ($deposit->accrue_times < self::MAX_ACCRUE_TIMES - 1) {
                $transaction->type = Transaction::TYPE_ACCRUE;
            } else {
                $transaction->type = Transaction::TYPE_CLOSE_DEPOSIT;
                $deposit->active = false;
            }

            $deposit->accrue_times += 1;
            $deposit->save();
            $transaction->save();
            $wallet->save();
        }
    }
}
