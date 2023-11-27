<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Hash;
use Auth;
use Validator;

class AuthController extends BaseController
{
   public function pharmacyRegister(Request $request)
   {
        $validator =Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'userName' => ['required', 'string', 'max:255'],
            'userNumber' => ['required', ],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError('Validate your data' , $validator->errors());
        }
         $input= $request->all();
         $input['password']=Hash::make( $input['password']);
         $user=User::create($input);

         $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
         $success['name']=$user->name;
         return $this->sendResponse($success , 'registration done successfully');
   }

   public function pharmacyLogin(Request $request){
    $data = [
        'userNumber' => $request->userNumber,
        'password' => $request->password
    ];

    if (Auth::attempt($data)) {
        $user=Auth::user();

        $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
        $success['name']=$user->name;
        return $this->sendResponse($success , 'login done successfully');
    }
    else {
        return $this->sendError('Unauthorized' , ['error ,Unauthorized']);
    }
   }

   public function logout(Request $request)
   {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        $success = 200;
        return $this->sendResponse( $success,[
            'message' => 'Successfully logged out'
        ]);

   }


   public function WareHouseLogin(Request $request){
    $data = [
        'email' => $request->email,
        'password' => $request->password
    ];

    if (Auth::attempt($data)) {
        $user=Auth::user();

        $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
        $success['name']=$user->name;
        return $this->sendResponse($success , 'login done successfully');
    }
    else {
        return $this->sendError('Unauthorized' , ['error ,Unauthorized']);
    }

   }
}
