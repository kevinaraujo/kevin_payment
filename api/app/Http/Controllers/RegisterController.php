<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params = ['name', 'cpfcnpj', 'email', 'password'];

            foreach ($params as $param) {
                if (!$request->has($param)) {
                   throw new \Exception('MISSING_PARAM');
                }
            }

            return response()->json([
                'message' => 'success'
            ]);

        } catch (\Exception $ex) {

            return response()->json([
                'message' => $ex->getMessage()
            ], 400);
        }
    }
}
