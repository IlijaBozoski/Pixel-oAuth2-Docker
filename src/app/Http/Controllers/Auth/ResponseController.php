<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ResponseController extends Controller
{
    public function deny()
    {
        return response()->json([
            'message'=>'Unauthorized'
        ],401);
    }
}
