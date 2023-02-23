<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\ResponseService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors(), 'Validation error', 400);
        }

        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->toArray());
        $response = [
            'user' => $user,
            'token' => $user->createToken('authToken')->plainTextToken,
        ];
        return ResponseService::success($response, 'User registered successfully');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $response = [
                'token' => $user->createToken('authToken')->plainTextToken,
                'user' => $user,
            ];
            return ResponseService::success($response, 'User logged in successfully');
        } else {
            return ResponseService::error(null, 'Invalid credentials', 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ResponseService::success(null, 'User logged out successfully');
    }
}
