<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionsController extends Controller
{
    public function index(Request $request) : JsonResponse
    {
        die('ok');
    }
}
