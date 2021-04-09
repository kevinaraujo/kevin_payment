<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Payment\CheckIfPayerCanSendMoney;
use App\Services\Payment\CheckIfUserPaymentIsAvailable;
use App\Services\Payment\UpdateUsersBalance;
use App\Services\Payment\ValidateTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {

            $payer = User::find($request->input('payer'));
            $payee = User::find($request->input('payee'));
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

            $validateTransaction = new ValidateTransaction($transaction);
            $response = $validateTransaction->execute();

            DB::beginTransaction();

            $updateUserBalance = new UpdateUsersBalance($transaction);
            $response = $updateUserBalance->execute();

            DB::commit();

            return response()->json([
                'message' => 'success'
            ], Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : Response::HTTP_BAD_REQUEST);

        }
    }
}
