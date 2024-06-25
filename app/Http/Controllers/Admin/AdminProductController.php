<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = AdminProduct::paginate(10);
        return $products;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:1000',
            'long_description' => 'required|string|max:6000',
            'quantity' => 'required|integer|min:1',
            'live' => 'required|boolean',
            'price' => 'required|numeric|min:1|max:99998.99|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'required|numeric|max:99999.99|lt:price|regex:/^\d+(\.\d{1,2})?$/',
            'expires_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'sku' => 'required|string|max:255|unique:products',
            'category_id' => 'required|integer|exists:categories,id',
            'published' => 'required|boolean',
            'image' => 'required|array|max:1',
            'image.*' => 'mimes:jpg,png|max:2048',
            'additional_images' => 'array|max:20',
            'additional_images.*' => 'image|mimes:jpg,png|max:2048',
            'colors' => 'required|max:20',
            'colors.*' => 'string|max:255|distinct',
            'sizes' => 'required|distinct',
            'sizes.*' => ['string', 'max:255', 'distinct', 'regex:/^(S|M|L|X*L)$/i'],
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $imagePaths = [];
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $imagePaths[] = 'uploads/' . $filename;
            }
        }

        $additionalImagePaths = [];
        if ($request->hasFile('additional_images')) {
            $files = $request->file('additional_images');
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $additionalImagePaths[] = 'uploads/' . $filename;
            }
        }

        $productData = $request->all();
        if (!empty($imagePaths)) {
            $productData['image'] = $imagePaths[0]; // Assuming single image upload
        }
        if (!empty($additionalImagePaths)) {
            $productData['additional_images'] = json_encode($additionalImagePaths); // Store as JSON array
        }

        $product = AdminProduct::create($productData);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }


   public function update(Request $request, $productId = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'short_description' => 'string|max:1000',
            'long_description' => 'string|max:6000',
            'quantity' => 'integer|min:1',
            'live' => 'boolean',
            'price' => 'numeric|min:1|max:99998.99|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'numeric|max:99999.99|lt:price|regex:/^\d+(\.\d{1,2})?$/',
            'expires_at' => 'date_format:Y-m-d H:i:s|after:now',
            'sku' => 'string|max:255|unique:products,sku,' . $productId,
            'category_id' => 'integer|exists:categories,id',
            'published' => 'boolean',
            'image' => 'array|max:1',
            'image.*' => 'mimes:jpg,png|max:2048',
            'additional_images' => 'array|max:20',
            'additional_images.*' => 'image|mimes:jpg,png|max:2048',
            'colors' => 'max:20',
            'colors.*' => 'string|max:255|distinct',
            'sizes' => 'distinct',
            'sizes.*' => ['string', 'max:255', 'distinct', 'regex:/^(S|M|L|X*L)$/i'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $imagePaths = [];
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $imagePaths[] = 'uploads/' . $filename;
            }
        }

        $additionalImagePaths = [];
        if ($request->hasFile('additional_images')) {
            $files = $request->file('additional_images');
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $additionalImagePaths[] = 'uploads/' . $filename;
            }
        }

        $productData = $request->all();
        if (!empty($imagePaths)) {
            $productData['image'] = $imagePaths[0]; // Assuming single image upload
        }
        if (!empty($additionalImagePaths)) {
            $productData['additional_images'] = json_encode($additionalImagePaths); // Store as JSON array
        }

        if ($productId) {
            $product = AdminProduct::find($productId);
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            $product->update($productData);
        } else {
            $product = AdminProduct::create($productData);
        }

        return response()->json([
            'message' => $productId ? 'Product updated successfully' : 'Product created successfully',
            'data' => $product
        ]);

}

public function destroy(AdminProduct $product)
{
    $product->delete();
    return response()->json([
        'message' => 'Product delete successfully',
        'data' => $product
    ]);
}

public function active()
{
    $product = AdminProduct::where('live',1)->where('expires_at','<',now())->get();



    return  $product;

    // response()->json(['success', 'Product status updated successfully']);
}

}
