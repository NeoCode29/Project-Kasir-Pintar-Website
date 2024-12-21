<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProductByStore($storeId)
    {
        $products = Product::where("store_id", $storeId)->get();

        if ($products->isEmpty()) {
            return response()->json(
                [
                    "message" => "No products found for this store",
                ],
                404
            );
        }

        return response()->json(
            [
                "message" => "Products retrieved successfully",
                "products" => $products,
            ],
            200
        );
    }

    public function storeProduct(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "name_product" => "required|string",
            "code_product" => "required|string|unique:products",
            "selling_price" => "required|numeric",
            "purchase_price" => "required|numeric",
            "stock" => "required|numeric",
            "unit" => "required|string",
            "url_image" => "nullable|string|url",
            "store_id" => "required|numeric|exists:stores,id",
            "category_product_id" =>
                "required|numeric|exists:category_products,id",
        ]);

        if ($validatedData->fails()) {
            return response()->json(
                ["errors" => $validatedData->errors()],
                422
            );
        }

        $product = Product::create($request->all());
        return response()->json(
            [
                "message" => "Product created successfully",
                "product" => $product,
            ],
            201
        );
    }

    public function updateProduct(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            "name_product" => "required|string",
            "code_product" =>
                "required|string|unique:products,code_product," . $id,
            "selling_price" => "required|numeric",
            "purchase_price" => "required|numeric",
            "stock" => "required|numeric",
            "unit" => "required|string",
            "url_image" => "nullable|string|url",
            "store_id" => "required|numeric|exists:stores,id",
            "category_product_id" =>
                "required|numeric|exists:category_products,id",
        ]);

        if ($validatedData->fails()) {
            return response()->json(
                ["errors" => $validatedData->errors()],
                422
            );
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(["message" => "Product not found"], 404);
        }

        $product->update($request->all());

        return response()->json(
            [
                "message" => "Product updated successfully",
                "product" => $product,
            ],
            200
        );
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(["message" => "Product not found"], 404);
        }
        $product->delete();
        return response()->json(
            ["message" => "Product deleted successfully"],
            200
        );
    }

    public function getAllCategoryProduct()
    {
        $categoryProducts = CategoryProduct::all();
        return response()->json(
            [
                "message" => "All category product retrieved successfully",
                "categoryProducts" => $categoryProducts,
            ],
            200
        );
    }
}
