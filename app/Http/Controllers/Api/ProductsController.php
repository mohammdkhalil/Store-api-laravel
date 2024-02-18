<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    /**
     * @param name
     * @param category
     *
     * @return Array<Product>
     */
    public function show(Product $product): JsonResponse
    {
        $res = Product::where('id', $product->id)->get();
        return response()->json(
            [
                'message' => 'retreived product succesfully',
                'data' => $res,
            ],
            200
        );
    }
    public function store(Request $request): JsonResponse
    {
        if (empty($request)) {
            return response()->json(
                [
                    'message' => 'there is no data for add',
                ],
                400
            );
        }
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'category' => 'required',
        ]);
        Product::create(
            [
                'name' => $request['name'],
                'description' => $request['description'],
                'image' => $request['image'],
                'price' => $request['price'],
                'category' => $request['category'],
            ]
        );
        return response()->json([
            'message' => 'product has been added successfully',
        ], 200);
    }
    public function update(Request $request, Product $product): JsonResponse
    {
        if (empty($request)) {
            return response()->json(
                [
                    'message' => 'there is no data for add',
                ],
                400
            );
        }
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'category' => 'required',
        ]);
        $newProduct = Product::where('id', $product->id)->update(
            [
                'name' => $request['name'],
                'description' => $request['description'],
                'image' => $request['image'],
                'price' => $request['price'],
                'category' => $request['category'],
            ]
        );
        return response()->json([
            'message' => 'product has been updated successfully',
        ], 200);
    }
    public function GetProducts(): JsonResponse
    {
        $proudcts = Product::all();

        return response()->json([
            'message' => 'products has been retreived successfully',
            'data' => $proudcts,
        ], 200);
    }

    /**
     * @return List of Category
     *
     */
    public function GetCategories(): JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            'message' => 'Categroies has been retreived successfully',
            'data' => $categories,
        ], 200);
    }

    public function createCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'desc' => ['nullable', 'string'],
            'image' => ['required','string']
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }
        try {
            $categories = Category::create([
                'name' => $request->input('name'),
                'desc' => $request->input('desc'),
                'image' => $request->input('image')
            ]);
            // $categories->save();
            return response()->json([
                "message" => "Category created Successfully",
                "data" => $categories
            ], 201);
        } catch (
            \Exception $e
        ) {
            // return error message if there is an exception
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }
    public function destroyCategory($categoryId)
    {
        try {
            // Check if the category exists
            $category = Category::find($categoryId);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            // Begin a database transaction
            DB::beginTransaction();

            // Delete the category
            $category->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollBack();

            return response()->json(['message' => 'Failed to delete category', 'error' => $e->getMessage()], 500);
        }
    }
}
