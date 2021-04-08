<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetupUser
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute() : void
    {
        $request = $this->request;

        $userClass = UserIdentifierFactory::execute($request->input('document'));
        $userType = UserType::where('code', $userClass->getCode())->first();

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'document' => $request->input('document'),
            'balance' => 0,
            'user_type_id' => $userType->id
        ]);
    }
}
