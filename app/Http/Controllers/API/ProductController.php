<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = ProductResource::collection(Product::get());

            return $products;
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();

            $product = Product::create($validatedData);
            DB::commit();
            return response()->json([
                'message' => 'Product created successfully',
                'data' => new ProductResource($product)
            ], 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product)
                return response()->json([
                    'message' => 'Product not found',
                ], 404);

            return new ProductResource($product);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            if (!$product)
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            $validatedData = $request->validated();
            $product->update($validatedData);
            DB::commit();
            return response()->json([
                'message' => 'Product updated successfully',
                'data' => new ProductResource($product)
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product)
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            $product->delete();
            return response()->json([
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }
}
