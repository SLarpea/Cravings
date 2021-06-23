<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Jobs\SendEmail;
use JWTAuth;
use Validator;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('throttle:5,5')->only('login');
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $message){
                return response()->json(['message' => $message])->header('code', 400);
            }
        }

        $email = ['email' => $request->input('email')];

        User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]));

        SendEmail::dispatch($email);

        return response()->json(['message' => "User successfully registered"])->header('code', 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $message){
                return response()->json(['message' => $message])->header('code', 401);
            }
        }

        if(! $token = JWTAuth::attempt($validator->validated())){
            return response()->json(['message' => 'Invalid credentials'])->header('code', 401);
        }

        return response()->json(['access_token' => $token])->header('code', 201);
    }
}
