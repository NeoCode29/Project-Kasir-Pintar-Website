<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use App\Models\Address;

class StoreController extends Controller
{
    public function getStoresByIdOwner($owner_id)
    {
        $stores = Store::where("owner_id", $owner_id)->get();
        return response()->json($stores);
    }

    public function getStoreByIdStore($store_id)
    {
        $store = Store::with("address")->find($store_id);
        if (!$store) {
            return response()->json(["message" => "Store not found"], 404);
        }
        return response()->json($store);
    }

    public function createStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "owner_id" => "required|integer",
            "name" => "required|string",
            "number_phone" => "nullable|string",
            "longitude" => "nullable|numeric",
            "latitude" => "nullable|numeric",
            "postal_code" => "nullable|string",
            "jalan" => "nullable|string",
            "provinsi" => "nullable|string",
            "kota" => "nullable|string",
            "negara" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $existingStore = Store::where("owner_id", $request->owner_id)
            ->where("name", $request->name)
            ->first();
        if ($existingStore) {
            return response()->json(
                [
                    "message" =>
                        "Store with this name already exists for this owner.",
                ],
                422
            );
        }

        $store = Store::create([
            "owner_id" => $request->owner_id,
            "name" => $request->name,
            "number_phone" => $request->number_phone,
        ]);

        $store->address()->create([
            "longitude" => $request->longitude,
            "latitude" => $request->latitude,
            "postal_code" => $request->postal_code,
            "jalan" => $request->jalan,
            "provinsi" => $request->provinsi,
            "kota" => $request->kota,
            "negara" => $request->negara,
        ]);

        return response()->json($store, 201);
    }

    public function deleteStore(int $id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(["message" => "Store not found"], 404);
        }

        $store->address()->delete();
        $store->delete();

        return response()->json(null, 204);
    }

    public function updateStore(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "number_phone" => "nullable|string",
            "longitude" => "nullable|numeric",
            "latitude" => "nullable|numeric",
            "postal_code" => "nullable|string",
            "jalan" => "nullable|string",
            "provinsi" => "nullable|string",
            "kota" => "nullable|string",
            "negara" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $store = Store::findOrFail($id);

        $store->update($request->all());

        $address = $store->address()->first();
        if ($address) {
            $address->update([
                "longitude" => $request->input("longitude"),
                "latitude" => $request->input("latitude"),
                "postal_code" => $request->input("postal_code"),
                "jalan" => $request->input("jalan"),
                "provinsi" => $request->input("provinsi"),
                "kota" => $request->input("kota"),
                "negara" => $request->input("negara"),
            ]);
        } else {
            Address::create([
                "id_store" => $store->id,
                "longitude" => $request->input("longitude"),
                "latitude" => $request->input("latitude"),
                "postal_code" => $request->input("postal_code"),
                "jalan" => $request->input("jalan"),
                "provinsi" => $request->input("provinsi"),
                "kota" => $request->input("kota"),
                "negara" => $request->input("negara"),
            ]);
        }

        return response()->json($store);
    }
}
