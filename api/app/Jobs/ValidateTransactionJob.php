<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\Payment\UpdateUsersBalance;
use App\Services\Payment\ValidateTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ValidateTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->queue = 'validate_transaction';
    }

    public function handle(): void
    {
        DB::beginTransaction();

        try {

            $validateTransaction = new ValidateTransaction($this->transaction);
            $response = $validateTransaction->execute();           

            $this->transaction->update([
                'status' => Transaction::STATUS_SUCCESS
            ]);

            DB::commit();

        }
        // @codeCoverageIgnoreStart
        catch (\Exception $ex) {
            DB::rollBack();

            $updateUserBalance = new UpdateUsersBalance($this->transaction, true);
            $response = $updateUserBalance->execute();

            $this->transaction->update([
                'status' => Transaction::STATUS_ERROR,
                'status_message' => $ex->getMessage()
            ]);

            $this->fail($ex->getMessage());
        }
        // @codeCoverageIgnoreEnd
    }
}
