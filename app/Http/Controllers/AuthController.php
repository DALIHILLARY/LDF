<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
        //Register a new user
        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admin_users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);
        }

        //login function
        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['token' => $token]);
        }


        //logout function
        public function logout(Request $request)
        {
            $token = $request->bearerToken();
            
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 400);
            }
        
            try {
                // Check if the token is valid before invalidating it
                if (!JWTAuth::parseToken()->check()) {
                    return response()->json(['error' => 'Token is invalid or expired'], 401);
                }
        
                // Invalidate the token
                JWTAuth::parseToken()->invalidate();
        
                return response()->json(['message' => 'Successfully logged out'], 200);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['error' => 'Token is invalid'], 401);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['error' => 'Could not invalidate token: ' . $e->getMessage()], 500);
            }
        }


        //function to get the authenticated user
        public function getAuthenticatedUser()
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
        
                if (!$user) {
                    return response()->json(['error' => 'user_not_found'], 404);
                }
        
                return response()->json(compact('user'));
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['error' => 'token_expired', $e->getMessage()], 500);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['error' => 'token_invalid', $e->getMessage()],401);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['error' => 'token_absent', $e->getMessage()],500);
            }
        }
        
        
        
        
}
