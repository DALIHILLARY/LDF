<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRoleUser;
use App\Models\User;
use App\Models\Utils;
use Tymon\JWTAuth\Facades\JWTAuth;
use Encore\Admin\Auth\Database\Administrator;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        header('Content-Type: application/json');

        $requestUrl = request()->path();
        $segments = explode('/', $requestUrl);
        $lastSegment = end($segments);

        if ($lastSegment != 'login' && $lastSegment != 'register') {
            $u = auth('api')->user();
            if ($u == null) {
                die(json_encode(['code' => 0, 'message' => 'Unauthorized']));
            }
        }
        // die("my api");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        die('test');
    }
    public function profile()
    {
        $u = auth('api')->user();
        if ($u == null) {
            return Utils::apiError('Unauthorized');
        }
        return Utils::apiSuccess($u);
    }

    
    public function register(Request $request)
    {
        if ($request->name == null) {
            return Utils::apiError('Name is required.');
        }
        if ($request->email == null) {
            return Utils::apiError('Email is required.');
        }
        if ($request->password == null) {
            return Utils::apiError('Password is required.');
        }
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();
        if ($user != null) {
            return Utils::apiError('Email already exists.');
        }
        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->username = $request->email;
        $newUser->password = password_hash($request->password, PASSWORD_DEFAULT);
        
        try {
            $newUser->save();
            $role = new AdminRoleUser();
            $role->user_id = $newUser->id;
            $role->role_id = 3;
            $role->save();
        } catch (\Throwable $th) {
            return Utils::apiError('Error saving user. ' . $th->getMessage());
        }

        $loggedinUser = User::where('email', $request->email)->first();

        JWTAuth::factory()->setTTL(60 * 24 * 30 * 12);
        $token = auth('api')->attempt([
            'email' => $loggedinUser->email,
            'password' => $request->password,
        ]);
        $loggedinUser->token = $token;
        return Utils::apiSuccess($loggedinUser, 'User registered successfully.');
    }

    public function login(Request $request)
    {

        if ($request->username == null) {
            return Utils::apiError('Username is required.');
        }
        if ($request->password == null) {
            return Utils::apiError('Password is required.');
        }
        $u = Administrator::where('username', $request->username)
        ->with('roles') // Assuming 'role' is the name of the relationship in your User model
        ->first();
        if ($u == null) {
            return Utils::apiError('User account not found.');
        }


        JWTAuth::factory()->setTTL(60 * 24 * 30 * 12);
        $token = auth('api')->attempt([
            'username' => $u->username,
            'password' => $request->password,
        ]);

        if ($token == null) {
            return Utils::apiError('Invalid credentials.');
        }
        $u->token = $token;
        return Utils::apiSuccess($u, 'User logged in successfully.');
    }
}
