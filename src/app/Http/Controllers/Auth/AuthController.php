<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
//use App\QueryClass;
use App\QueryClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');


        return response()->json([

            'authorization' => [
                'token' =>$tokenResult->accessToken,
                'type' => 'bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ],
            'user' => $user
        ]);

    }

    public function pixelCreate(Request $request)
    {
        $user=Auth::user();
        $reg = Registration::create([
            'user_id' => $user->id,
            'portal_id' => $request->portal_id,
            'type' => $request->type,
            'occurred_on' => Carbon::parse(now())->toDateTimeString()

        ]);
        return response()->json([
            'pixelType' => $request->type,
            'userId' => $reg->user->id,
            'occurredOn' => $reg->occurred_on,
            'portalId' => $reg->portal_id,
            'CLIENTS'=>QueryClass::getClients($reg->user->id)

        ], 200);



    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}

