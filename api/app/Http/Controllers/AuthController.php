<?php

namespace App\Http\Controllers;

use App\Rules\EmailRule;
use App\Services\Auth\Jwt;
use App\Services\User\AuthenticateUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {

            $this->validate($request, [
                'email' => 'required',
                'password' => 'required'
            ]);

            $user = AuthenticateUser::execute(
                $request->input('email'),
                $request->input('password')
            );

            $claims = [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'document' => $user->document
                ]
            ];

            $generateJwt = new Jwt();
            $newJwt = $generateJwt->generate($claims);

            return response()->json([
                'message' => 'success',
                'access_token' => $newJwt
            ]);

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
}
