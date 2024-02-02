<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Traits\ApiResponder;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponder;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email','regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix','max:100','unique:users'],
            'name' => 'required|string|regex:/^[\pL\s\-]+$/u|max:20',
            'password' => 'required|max:200',
            'c_password' => 'required|same:password',

        ]);

        $data = [];

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }else{
            $input = $request->all();
            $input['role'] = 'admin';
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $data['token'] = $user->createToken('MyApp')->plainTextToken;
            $data['name'] = $user->name;
            return $this->success($data,'User registered successfully!!', 200);

        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email','regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix','max:100'],
            'password' => 'required|max:200',

        ]);

        $data = [];

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
       if(Auth::attempt(['email' => $request->email,'password'=>$request->password]))
       {
            $user = Auth::user();
            $data['token'] = $user->createToken('auth-token')->plainTextToken;
            $data['name'] = $user->name;
            return $this->success($data,'User login successfully!!', 200);
       }else{
        $data['errors'] = 'wrong email id or password';
        return $this->error('All fields are required and should be valid', 422 ,$data);
       }
    }

    public function logout(Request $request)
    {
        
        $data = [];
            auth('sanctum')->user()->tokens()->delete();
            $user = Auth::user();

            // Revoke the user's token
            $user->tokens()->delete();
            return $this->success($data,'Successfully logged out', 200);         
    
        

    }

    
}
