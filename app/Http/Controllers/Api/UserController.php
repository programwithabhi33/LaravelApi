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
    public function index(Request $request)
    {
        echo "user index method";
    }

    /**
     * Show the form for creating a new resource.
     */
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
