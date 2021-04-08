<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use App\Rules\DocumentRule;
use App\Rules\EmailRule;
use App\Services\Format\FormatDocument;
use App\Services\User\CheckIfUserExists;
use App\Services\User\SetupUser;
use Illuminate\Http\Request;
use Emarref\Jwt\Claim;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function index(Request $request) : JsonResponse
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
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e ) {
            return response()->json([
                'message' => $e->errors()
            ],400);

        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : 400);
        }
    }
}
