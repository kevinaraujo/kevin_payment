<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use App\Rules\DocumentRule;
use App\Rules\EmailRule;
use App\Services\Format\FormatDocument;
use App\Services\Register\CheckIfUserExists;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        try {

            $this->validate($request, [
                'name' => ['required'],
                'document' => ['required', new DocumentRule],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'max:12']
            ]);

            $formatDocument = new FormatDocument($request->input('document'));
            $document = $formatDocument->execute();

            $user = User::query()
                ->where('email', $request->input('email'))
                ->orWhere('document', $document)
                ->first();

            if ($user) {
                CheckIfUserExists::execute(
                    $user,
                    $request->input('email'),
                    $document
                );
            }

            $userType = UserType::where('code', 'client')->first();

            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'document' => $document,
                'balance' => 0,
                'user_type_id' => $userType->id
            ]);

            return response()->json([
                'message' => 'success'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e ) {
            return response()->json([
                'message' => $e->errors()
            ],400);

        } catch (\Exception $ex) {

            return response()->json([
                'message' => $ex->getMessage()
            ], 400);
        }
    }
}
