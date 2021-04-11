<?php

namespace App\Http\Controllers;

use App\Jobs\ValidateTransactionJob;
use App\Models\Transaction;
use App\Services\Payment\CheckIfPayerCanSendMoney;
use App\Services\Payment\CheckIfUserPaymentIsAvailable;
use App\Services\Payment\CheckIfUsersExist;
use App\Services\Payment\UpdateUsersBalance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionsController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        try {

            $this->validate($request, [
                'payer' =>'required',
                'payee' => 'required',
                'payment_type' => 'required',
                'value' => 'required',
                'description' => 'required'
            ]);

            $payerId = $request->input('payer');
            $payeeId = $request->input('payee');

            $check = new CheckIfUsersExist($payerId, $payeeId);
            list($payer, $payee) = $check->execute();

            $value = $request->input('value');
            $paymentTypeId = $request->input('payment_type');

            $check = new CheckIfUserPaymentIsAvailable($payer, $paymentTypeId);
            $response = $check->execute();

            $check = new CheckIfPayerCanSendMoney($payer, $payee, $value);
            $response = $check->execute();

            $transaction = Transaction::create([
                'payer_id' => $payer->id,
                'payee_id' =>  $payee->id,
                'user_payment_id' =>  $paymentTypeId,
                'value' =>  $request->input('value'),
                'description' =>  $request->input('description'),
                'status' => Transaction::STATUS_PENDING
            ]);

            $updateUserBalance = new UpdateUsersBalance($transaction);
            $response = $updateUserBalance->execute();

            ValidateTransactionJob::dispatch($transaction);

            return response()->json([
                'message' => 'success',
                'id' => $transaction->id
            ], Response::HTTP_CREATED);

        } catch (ValidationException $ex ) {

            return response()->json([
                'message' => $ex->errors()
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : Response::HTTP_BAD_REQUEST);

        }
    }
}
