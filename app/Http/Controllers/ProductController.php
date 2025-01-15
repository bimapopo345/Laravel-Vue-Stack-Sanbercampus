<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * List all products (Public)
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products, 200);
    }

    /**
     * Show single product detail (Public)
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found.'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Create a new product (Admin)
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|uuid|exists:categories,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Handle image upload
        if($request->hasFile('image')){
            try {
                Log::info('Mulai mengupload gambar ke Cloudinary.');
                $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'products', // Optional: Tentukan folder di Cloudinary
                ]);
                $uploadedFileUrl = $uploadedFile->getSecurePath();
                Log::info('Gambar berhasil diupload: ' . $uploadedFileUrl);
            } catch (\Exception $e) {
                Log::error('Error saat mengupload gambar ke Cloudinary: ' . $e->getMessage());
                return response()->json(['message' => 'Gagal mengupload gambar.'], 500);
            }
        } else {
            $uploadedFileUrl = null;
        }

        // Buat produk
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $uploadedFileUrl,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        return response()->json(['message' => 'Produk berhasil dibuat.', 'product' => $product], 201);
    }

    /**
     * Update a product (Admin)
     */
    public function update(Request $request, $id)
{
    // Tambahkan logging untuk data yang diterima
    Log::info('Update Request Data:', $request->all());

    $product = Product::find($id);
    if (!$product) {
        return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name'        => 'sometimes|required|string|max:255',
        'price'       => 'sometimes|required|integer|min:0',
        'description' => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'stock'       => 'sometimes|required|integer|min:0',
        'category_id' => 'sometimes|required|uuid|exists:categories,id',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $data = $request->only(['name','price','description','stock','category_id']);

    // Tambahkan logging sebelum update
    Log::info('Data yang akan diupdate:', $data);

    // Handle image upload
    if ($request->hasFile('image')) {
        try {
            Log::info('Mulai mengupload gambar ke Cloudinary.');
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'products',
            ]);
            $uploadedFileUrl = $uploadedFile->getSecurePath();
            $data['image'] = $uploadedFileUrl;
            Log::info('Gambar berhasil diupload: ' . $uploadedFileUrl);
        } catch (\Exception $e) {
            Log::error('Error saat mengupload gambar ke Cloudinary: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengupload gambar.'], 500);
        }
    }

    $product->update($data);
    $product->refresh();

    // Tambahkan logging setelah update
    Log::info('Produk setelah diupdate:', $product->toArray());

    return response()->json([
        'message' => 'Produk berhasil diperbarui.',
        'product' => $product
    ], 200);
}




public function destroy(Product $product)
{
    $product->delete();
    return response()->json(['message' => 'Produk berhasil dihapus.'], 200);
}

}
