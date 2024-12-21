<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function getProfile($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $profile = $user->profile()->first();

        if (!$profile) {
            return response()->json(["message" => "Profile not found"], 404);
        }

        return response()->json($profile, 200);
    }

    public function changeProfile(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_id" => "required|string",
            "number_phone" => "required|string|min:12|max:14",
            "gender" => "required|string",
            "age" => "required|string",
            "address" => "required|string|max:255",
            "profile_img" => "required|file|mimes:jpg,png,pdf|max:10240",
        ]);

        if ($validatedData->fails()) {
            return response()->json(
                [
                    "message" => "Validasi gagal",
                    "errors" => $validatedData->errors(),
                ],
                422
            );
        }

        $file_path = null;
        if ($request->hasFile("profile_img")) {
            $file = $request->file("profile_img");
            $customFileName =
                "profile" . "." . $file->getClientOriginalExtension();
            $file_path = $file->storeAs(
                "uploads/" . $request->input("user_id"),
                $customFileName,
                "public"
            );
            $user = User::find((int) $request->input("user_id"));

            if (!$user) {
                return response()->json(
                    ["message" => "User tidak ditemukan"],
                    404
                );
            }

            $user->update([
                "number_phone" => $request->input("number_phone"),
            ]);

            if ($user->profile()->exists()) {
                $user->profile()->update([
                    "gender" => $request->input("gender", "none"),
                    "age" => (int) $request->input("age"),
                    "address" => $request->input("address"),
                    "url_image" => $file_path,
                ]);
            } else {
                $user->profile()->create([
                    "gender" => $request->input("gender", "none"),
                    "age" => (int) $request->input("age"),
                    "address" => $request->input("address"),
                    "url_image" => $file_path,
                ]);
            }

            return response()->json(
                ["message" => "berhasil update profile"],
                200
            );
        }

        return response()->json(["message" => "berhasil update profile"], 200);
    }

    public function findUserByName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "message" => "Validasi gagal",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        $name = $request->name;
        $users = User::where("name", "LIKE", "%$name%")
            ->with("profile")
            ->get();

        if ($users->isEmpty()) {
            return response()->json(
                ["message" => "User tidak ditemukan", "users" => $users],
                404
            );
        }

        return response()->json(["users" => $users]);
    }
}
