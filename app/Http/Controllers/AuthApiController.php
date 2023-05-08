<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class AuthApiController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['refresh','login', 'register','registerUser']]);
    }
 
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
     
    public function registerUser (Request $request) 
    {
       
           
                        
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'email' => 'required|email',
               
            ]);
       
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            else{
                    $file_name='';
                    $user_exit = User::where('phone',$request->phone)->first();
                    $user_email = User::where('email',$request->email)->first();
                    
                    if($user_email){

                        return response()->json([
                            'status'=>404,
                            'message'=>'User Email aleady exits!',
                        ]);

                    }

                    if($user_exit){

                        return response()->json([
                            'status'=>400,
                            'message'=>'User Phone aleady exits!',
                        ]);

                    }

                    if($request->hasFile('image')) {

                        $file = $request->file('image');
    
                        $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
    
                        $image['filePath'] = $name;
    
                        $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;
    
                        $file->move(public_path().'/userProfiles/', $file_name);
    
                    }
                    
                     
                    User::create([
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'image' => $file_name,
                    ]);

                    return response()->json([
                        'status'=>200,
                        'message'=>'Registered Successfully',
                    ]);
            }
    }

 
 
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email_phone' => 'required',
            'password' => 'required',
        ]);
        
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            else{
                
                $email_phone = $request->email_phone;

                if (filter_var($email_phone, FILTER_VALIDATE_EMAIL)) {
                    $user_exit = User::where('email', $email_phone)->first();
                    $credentials = [
                        'email' => $email_phone,
                        'password' => $request->password,
                    ];
                } else {
                    $user_exit = User::where('phone', $email_phone)->first();
                    $credentials = [
                        'phone' => $email_phone,
                        'password' => $request->password,
                    ];
                }

                    if($user_exit){
    
                        if (!Hash::check($request->password, $user_exit->password)) {
                            return response()->json([
                                 'status'=>400,
                                 'message' => 'Login Fail, pls check password'
                                ]);
                        }
                        else{
                            if (Auth::guard('jwt')->attempt($credentials)) {
                                    $token = JWTAuth::fromUser(Auth::guard('jwt')->user());
                                    return response()->json(['token' => $token]);
                                } else {
                                    return response()->json(['error' => 'Invalid credentials'], 401);
                                }
                        }
                    }

                    else{
                        return response()->json(['message' => 'user not Found'],400);
                    }
            }
    }
    
    
    public function getTokenForUser(Request $request)
    {
        $userId = $request->user_id;
    
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    
        $token = JWTAuth::fromUser($user);
    
        return response()->json([
            'token' => $token
        ]);
    }

    public function me()
    {
        $token = Auth::guard('jwt')->user();

        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */public function logout()
    {
        auth()->logout();
 
        return response()->json(['message' => 'Successfully logged out']);
    }
 
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $userId = $request->userId;

        $user = User::find($userId);
    
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    
        $token = JWTAuth::fromUser($user);
    
        return response()->json([
            'token' => $token
        ]);
    }
 
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}