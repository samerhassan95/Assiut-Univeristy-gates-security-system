<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Validator;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'showLoginForm',
            'showRegisterForm',
            'login',
            'register', 
        ]]);
    }



    public function register(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }
    
        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password)]
        ));

    if ($request->expectsJson()) {
        return response()->json(
            [
                'message'=>'User successfully registered',
                'user'=>$user
            ],201);
    } else {
        return redirect()->route('loginform');
    }
}
    



///////////////////////////////////////////////////////////////////

// public function loginWeb(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'email' => 'required|email',
//         'password' => 'required|string|min:6',
//     ]);

//     if ($validator->fails()) {
//         return redirect()->route('login')->withErrors($validator)->withInput();
//     }

//     if (!Auth::attempt($validator->validated())) {
//         return redirect()->route('login')->with('status', 'Invalid credentials');
//     }

//     return redirect()->route('dashboard');
// }

// public function registerWeb(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|confirmed|min:6',
//     ]);

//     if ($validator->fails()) {
//         return redirect()->route('register')->withErrors($validator)->withInput();
//     }

//     $user = User::create(array_merge(
//         $validator->validated(),
//         ['password' => bcrypt($request->password)]
//     ));

//     return redirect()->route('login')->with('message', 'User successfully registered');
// }





    public function login(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),422);
        }
if(!$token=auth()->attempt($validator->validated())){
return response()->json(['error'=>'Unauthorized'],401);
}
// if ($request->expectsJson()) {
//     return $this->createNewToken($token);
// } else {
    return redirect()->route('dashboard');
}

       
public function createNewToken($token){
    return response()->json([
        'acces_token'=>$token,
        'token_type'=>'bearer',
        'expires_in'=>auth()->factory()->getTTL()*60,
        'user'=>auth()->user()

    ]);

}





// public function loginWeb(Request $request)
// {
//     $validator= Validator::make($request->all(),[
//         'email' => 'required|email',
//         'password' => 'required|string|min:6',
//     ]);
//     if($validator->fails()){
//         return response()->json($validator->errors()->toJson(),422);
//     }
// if(!$token=auth()->attempt($validator->validated())){
// return response()->json(['error'=>'Unauthorized'],401);
// }
// // return $this->createNewToken($token);
// return redirect()->route('dashboard');

// }





public function logout(Request $request)
{
    auth()->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    
}






    public function showLoginForm()
    {
        return view('login'); // Change 'auth.login' to match your actual login view file
    }

    public function showRegisterForm()
    {
        return view('register'); // Change 'auth.register' to match your actual register view file
    }
   
    





}