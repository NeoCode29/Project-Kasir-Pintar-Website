<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{

    public function index($id_owner)
    {
        $stores = Store::where('id_owner', $id_owner)->get();
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_owner' => 'required|integer',
            'name' => 'required|string',
            'number_phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $store = Store::create($request->all());

        return response()->json($store, 201);
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return response()->json(null, 204);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'number_phone' => 'nullable|string',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'postal_code' => 'nullable|string',
            'jalan' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kota' => 'nullable|string',
            'negara' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $store = Store::findOrFail($id);

        $store->update($request->all());

        $address = $store->address()->first();
        if ($address) {
            $address->update([
                'longitude' => $request->input('longitude'),
                'latitude' => $request->input('latitude'),
                'postal_code' => $request->input('postal_code'),
                'jalan' => $request->input('jalan'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
                'negara' => $request->input('negara'),
            ]);
        } else {
            Address::create([
                'id_store' => $store->id,
                'longitude' => $request->input('longitude'),
                'latitude' => $request->input('latitude'),
                'postal_code' => $request->input('postal_code'),
                'jalan' => $request->input('jalan'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
                'negara' => $request->input('negara'),
            ]);
        }

        return response()->json($store);
    }

    
}
