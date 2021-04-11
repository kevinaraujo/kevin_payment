<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\DocumentRule;
use App\Services\Format\FormatDocument;
use App\Services\User\CheckIfUserExists;
use App\Services\User\SetupUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        try {

            $this->validate($request, [
                'name' => ['required'],
                'document' => ['required', new DocumentRule],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'max:12']
            ]);

            $formatDocument = new FormatDocument($request->input('document'));
            $request->merge([
                'document' => $formatDocument->getDocument()
            ]);

            CheckIfUserExists::execute(
                $request->input('email'),
                $request->input('document')
            );

            $setupUser = new SetupUser($request);
            $setupUser->execute();

            return response()->json([
                'message' => 'success'
            ], Response::HTTP_CREATED);

        } catch (ValidationException $ex ) {

            return response()->json([
                'message' => $ex->errors()
            ],Response::HTTP_BAD_REQUEST);

        } catch (\Exception $ex) {

            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : Response::HTTP_BAD_REQUEST);

        }
    }

    public function paymentTypes(string $userId, Request $request): JsonResponse
    {
        try {

            $user = User::find($userId);

            if (!$user) {
                throw new \Exception(
                    'USER_NOT_FOUND',
                    Response::HTTP_NOT_FOUND
                );
            }

            $paymentTypes = [];
            foreach ($user->userPayments as $userPayment) {
                $paymentTypes[$userPayment->paymentType->id] = $userPayment->paymentType->description;
            }

            return response()->json([
                'message' => 'success',
                'payment_types' => $paymentTypes
            ], Response::HTTP_OK);

        } catch (\Exception $ex) {

            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : Response::HTTP_BAD_REQUEST);

        }
    }

    public function balance(string $userId, Request $request): JsonResponse
    {
        try {

            $user = User::find($userId);

            if (!$user) {
                throw new \Exception(
                    'USER_NOT_FOUND',
                    Response::HTTP_NOT_FOUND
                );
            }

            return response()->json([
                'message' => 'success',
                'user' => [
                    'email' => $user->email,
                    'balance' => $user->balance
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $ex) {

            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : Response::HTTP_BAD_REQUEST);

        }
    }
}
