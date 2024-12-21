<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\Notification;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class NotificationController extends Controller
{
    public function index($user_id)
    {
        $notifications = Notification::where("user_id", $user_id)
            ->with("invites")
            ->get();
        return response()->json($notifications);
    }

    public function invite(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "sender_id" => "required|integer",
            "target_id" => "required|integer",
            "store_id" => "required|integer",
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->error(), 422);
        }

        $store = Store::find($request->store_id)->name;
        $owner = User::find($request->sender_id)->name;

        $notification = Notification::create([
            "user_id" => $request->target_id,
            "message" => "anda menerima undangan dari $owner untuk bekerja di $store",
        ]);

        $invite = $notification->invite()->create([
            "notification_id" => $notification->id,
            "sender_id" => $request->sender_id,
            "target_id" => $request->target_id,
            "store_id" => $request->store_id,
        ]);

        return response()->json(["message" => "berhasil membuat undangan"]);
    }

    public function setRead(Request $request)
    {
        $notification_id = $request->input("notification_id");
        $notification = Notification::find($notification_id);
        if (!$notification) {
            return response()->json(
                ["message" => "Notification not found"],
                404
            );
        }
        $notification->update(["is_read" => true]);
        return response()->json(["message" => "notifikasi telah dibaca"]);
    }

    public function acceptInvite(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "invite_id" => "required|integer",
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        $invite = \App\Models\Invite::find($request->invite_id);
        if (!$invite) {
            return response()->json(["message" => "Invite not found"], 404);
        }

        $invite->update(["status" => "accepted"]);
        return response()->json(["message" => "Invite accepted"]);
    }

    public function notAcceptInvite(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "invite_id" => "required|integer",
            "decision" => "required|boolean",
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        $invite = Invite::find($request->invite_id);
        if (!$invite) {
            return response()->json(["message" => "Invite not found"], 404);
        }

        $user_id = Notification::find($invite->notification_id)->user_id;

        if($request->decision){
                $staff = Staff::create([
                    "user_id" => $user_id,
                    "store_id" => $invite->store_id,
                ]);
            }
        }

        $invite->update(["is_accept" => $request->decision]);
        return response()->json(["message" => "Invite rejected"]);
    }
}
