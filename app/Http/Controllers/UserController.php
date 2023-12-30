<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = 1;
        $users = User::all();
        // dd($users);
        return $this->sendResponse([$users ] , 'Our pharmacies data retrived successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showPersonalInfo($id)
    {
        $user = User::find($id);
        // dd($user , Auth::user());
        // dd(Auth::user()->id);
        if(Auth::user()->id != $user->id){
            return $this->sendError('don\'t have permission to fetch this data' ,'' ,);
        }
        return $this->sendResponse($user ,'user data retrivied successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePesonalInfo(Request $request,  $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'userName' => ['required', 'string', 'max:255' ],
            'userNumber' => ['required' ,'digits:10' ],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('validate your data,' , $validator->errors());
        }
        // $users = User::all();
        // $errors = [];
        // $bool = false;
        // foreach($users as $one){
        //     if($user->userNumber == $one->userNumber){
        //         $bool = true;
        //         $errors = 'The user number has already been taken';
        //     }
        //     if($user->email == $one->email){
        //         $bool = true;
        //         $errors = 'The email has already been taken';
        //     }
        // }
        // if($bool){
        //     return $this->sendError('validate your data,' , $errors);
        // }

        $user->name = $request->name;
        $user->userName = $request->userName;
        $user->userNumber = $request->userNumber;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->confirm_password = $request->confirm_password;

        $user->save();
        return $this->sendResponse(new UserResource($user) ,  'updated data done successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
