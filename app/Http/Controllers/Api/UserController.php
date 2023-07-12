<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($flag)
    {
        // flag 1 => Active Users , 0 => All users
        $query = User::select("name","email");
        if($flag == 1){
            $query->where("status",1);
        }
        else if($flag == 0){
            // empty condition 
        }
        else{
            return response()->json([
                'message' => "Unknown parameter passed, it can be 1 or 0",
            ],400);
        }
        $users = $query->get();
        if(count($users) > 0){
            // at least 1 user in the database 
            $response = [
                'status' => true,
                'data' => $users,
            ];
        }
        else{
            // No user found in the database 
            $response = [
                'status' => false,
                'message' => "No users found",
            ];
        }
        return response()->json($response,200);
    }

   
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
            'email' => ['required','unique:users,email'],
            'password' => ['required','min:8','confirmed'],
            'password_confirmation' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),400);
        }
        else{
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];
            DB::beginTransaction();
            try{
                User::create($data);
                DB::commit();
                return response()->json([
                    'status' =>true,
                    'message' => "User Created successfully"
                ],200);
            }
            catch(\Exception $error){
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => "Something went wrong while creating user"
                ],500);
            }
        }
        
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(is_null($user)){
            $response = [
                'status' => false,
                'message' => "No user found",
            ];
        }
        else{
            $response = [
                'status' => true,
                'data' => $user,
            ];
        }
        return response()->json($response,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                'status' => false,
                'message' => "User with this id does not exist"
            ],404);
        }
        else{
            DB::beginTransaction();
            try{
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
                DB::commit();
            }
            catch(\Exeception $error){
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => "Internal server error"
                ],500);
            }
        }
        return response()->json([
            'status' => true,
            'message' => "User details updates successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(is_null($user)){
            // user not found 
            $response = [
                'status' => false,
                'message' => "User with this id not found",
            ];
            $responseCode = 404;
        }
        else{
            // User found with this id
            DB::beginTransaction();
            try{
                $user->delete();
                DB::commit();
                $response = [
                    'status' => true,
                    'message' => "User deleted successfully",
                ];
                $responseCode = 200;
            }catch(\Exeception $error){
                DB::rollback();
                $response = [
                    'status' => true,
                    'message' => "Inter server error",
                ];
                $responseCode = 500;
            }
        }
        return response()->json($response,$responseCode);
    }

    public function changePassword(Request $request, string $id) {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                'status' => false,
                'message' => "User with this id does not exist"
            ],404);
        }
        else{
            if(($request->new_password) == ($request->confirm_password)){
                if(!Hash::check($request->old_password, $user->password)){
                    return response()->json([
                        'status' => false,
                        'message' => "The old password is not correct",
                    ],400);
                }
                else{
                    DB::beginTransaction();
                    try{
                        $user->password = Hash::make($request->new_password);
                        $user->save();
                        DB::commit();
                    }
                    catch(\Exception $error){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message' => "Internal server error",
                        ],500);
                    }
                }
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => "New password and confirm password should be same",
                ],400);
            }
        }
        return response()->json([
            'status' => true,
            'message' => "User password changed successfully",
        ]);
    }

    public function register(Request $request){

        // When register user apply the validation's
        $validatedData = $request->validate([
            "name" => "required",
            "email" => ["required","email","unique:users,email"],
            "password" => ["required","min:8","confirmed"]
        ]);

        // Finally create user with the validatedData
        $user = User::create($validatedData);

        // Creating user token 
        $token = $user->createToken("auth_token")->accessToken;

        return response()->json([
            "status" => true,
            "token" => $token,
            "user_details" => $user,
            "message" => "User created successfully"
        ]);

    }
}
