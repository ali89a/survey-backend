<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

            event(new UserRegisteredEvent($data));
            $token=$data->createToken('tokens')->plainTextToken;
            if ($data){
                return response()->json([
                    'success' => true,
                    'message' => 'Registration Successful. Please login to your account.',
                    'data' => [
                        'user'=>new UserResource($data),
                        'token'=>$token
                    ]
                ], 200);
            }
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
        return response()->json([
            'success' => false,
            'message' => 'Registration Failed',
            'data' => null
        ], 500);

    }

    public function login(LoginRequest $request)
    {
        try {


        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('API Token')->plainTextToken;

                $data = [
                    'access_token' => $accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => null,
                    'userData' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                ];
                return response()->successResponse('Login successful', $data);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Password Did not matched',
                    'data' => null
                ], 401);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully Logged Out',
            'data' => null
        ], 200);
    }
    private function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => null,
                'userData' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ],

        ],200);
    }
}
