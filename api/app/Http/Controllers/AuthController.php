<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailRule;
use App\Services\Auth\Jwt;
use App\Services\User\AuthenticateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function index(Request $request) : JsonResponse
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

        } catch (\Illuminate\Validation\ValidationException $e ) {
            return response()->json([
                'message' => $e->errors()
            ],400);

        } catch (\Emarref\Jwt\Exception\VerificationException $ex) {
            return response()->json([
                'message' => $ex->errors()
            ],400);

        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : 400);
        }
    }
}
