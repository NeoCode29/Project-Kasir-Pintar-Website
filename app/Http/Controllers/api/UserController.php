<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Profile;
use App\Http\Controllers\Resources\UserResource;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Invalid input', 'errors' => $validatedData->errors()], 400);
        }

        $user = User::where('email', $validatedData->validated()['email'])->first();

        if (!$user || !Hash::check($validatedData->validated()['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['token' => $token, 'user_id' => $user->id]);
    }

    public function register(Request $request){
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Validasi gagal', "data" => $request->all(), 'errors' => $validatedData->errors()], 422);
        }

        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json(["data" => $user, "access_token" => $token], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'], 500);
        }

    }

    public function findEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json(['user_id' => $user->id]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required_with:new_password|same:new_password|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak benar'], 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function changeProfile(Request $request){
        $validatedData = Validator::make($request->all(), [
            'user_id' => "required|string",
            'number_phone' => "required|string|min:12|max:14",
            'gender' => 'required|string',  
            'age' => 'required|string',  
            'address' => 'required|string|max:255',  
            'profile_img' => 'required|file|mimes:jpg,png,pdf|max:10240',  
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validatedData->errors()], 422);
        }

        $file_path = null;
        if($request->hasFile("profile_img")){
            $file_path = $request->file('profile_img')->store('upload/'.$request->input("user_id"),"public");
            $user = User::find((int)$request->input("user_id"));
        
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $user->update([
                "number_phone" => $request->input("number_phone")
            ]);

            if($user->profile()->exists()){
                $user->profile()->update([
                    "gender" => $request->input("gender","none"),
                    "age" => (int)$request->input("age"),
                    "address" => $request->input("address"),
                    "url_image" => $file_path
                ]);
            }else{
                $user->profile()->create([
                    "gender" => $request->input("gender","none"),
                    "age" => (int)$request->input("age"),
                    "address" => $request->input("address"),
                    "url_image" => $file_path
                ]);
            }

           

            return response()->json(["message" => "berhasil update profile"], 200);
        }
        
        return response()->json(["message" => "berhasil update profile"], 200);
    }
}
