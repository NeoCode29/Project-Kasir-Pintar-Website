<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
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
            $file = $request->file('profile_img');
            $customFileName = 'profile' . '.' . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('public/uploads/'. $request->input("user_id"),$customFileName);
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
