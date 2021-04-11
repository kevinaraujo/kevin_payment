<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'message' => 'success'
            ]);

        } catch (\Exception $ex) {

            return response()->json([
                'message' => $ex->getMessage()
            ], $ex->getCode() ? $ex->getCode() : Response::HTTP_BAD_REQUEST);

        }
    }
}
