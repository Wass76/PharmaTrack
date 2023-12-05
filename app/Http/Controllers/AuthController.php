<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
   public function pharmacyRegister(Request $request)
   {
        $validator =Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'userName' => ['required', 'string', 'max:255' ],
            'userNumber' => ['required','unique:users' ],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'confirm_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError('Validate your data' , $validator->errors());
        }
         $input= $request->all();
         $input['password']=Hash::make( $input['password']);
         $user=User::create($input);

         $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
         $success['name']=$user->name;
         $success['userName'] = $user->userName;
         $success['userNumber'] = $user->userNumber;
         $success['address'] = $user->address;
         $success['email'] = $user->email;
         return $this->sendResponse($success , 'registration done successfully');
   }

   public function pharmacyLogin(Request $request){
    $data = [
        'userNumber' => $request->userNumber,
        'password' => $request->password
    ];

    $validator = Validator::make($request->all() , [
        'userNumber' => ['required'],
        'password' => ['required' , 'min:8']
    ]);

    if (Auth::attempt($data)) {
        $user=Auth::user();

        $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
        $success['name']=$user->name;
        $success['userName'] = $user->userName;
        $success['userNumber'] = $user->userNumber;
        $success['address'] = $user->address;
        $success['email'] = $user->email;
        return $this->sendResponse($success , 'login done successfully');
    }
    else {
        if($validator->fails()){
            return $this->sendError('Unauthorized' , $validator->errors());
        }
            return $this->sendError('Unauthorized' , ['user number or password isn\'t correct']);
            }

   }

   public function logout(Request $request)
   {
        if (auth()->check()) {
            auth()->user()->token()->revoke();
            $success = 200;
            return $this->sendResponse( $success,[
                'message' => 'Successfully logged out'
            ]);
        }
        else{
            return $this->sendError( ['Unauthenticated'],'You aren\'t signed in before');
        }
       



   }


   public function WareHouseLogin(Request $request){
    $data = [
        'email' => $request->email,
        'password' => $request->password
    ];
    $validator = Validator::make($request->all() , [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required' , 'min:8']
    ]);

    if (Auth::attempt($data)) {
        $user=Auth::user();

        $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
        $success['name']=$user->userName;
        return $this->sendResponse($success , 'login done successfully');
    }
    else {
        if($validator->fails()){
            return $this->sendError('Unauthorized' , $validator->errors());
        }
            return $this->sendError('Unauthorized' , ['user number or password isn\'t correct']);
            }

   }
}
