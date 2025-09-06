<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function viewProducts(Request $request)
{

    $products = Product::with('category')->get();
    $categories = Category::select('id_categories', 'name')->get();
    return response()->json([
        'products' => $products,
        'categories' => $categories,
    ]);
}
public function viewProductsMobile(Request $request)
{ 
    $products = Product::with('category')->get(); 
    return response()->json($products); 
}
    

    public function addProduct(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
            'is_available' => 'boolean',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'is_available' => $request->is_available ?? true,
        ]);

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

   public function updateProduct(Request $request, Product $product)
{
    $validatedData = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|nullable|string',
        'price' => 'sometimes|required|numeric|min:0',
        'category_id' => 'sometimes|required|integer|exists:categories,id_categories',
        'is_available' => 'sometimes|boolean',
    ]);

    if (empty($validatedData)) {
        return response()->json([
            'message' => 'No valid data provided for update. At least one field is required.'
        ], 400);
    }

    // Dengan $fillable, baris ini sekarang akan berhasil menyimpan data ke database
    $product->update($validatedData);

    // Baris ini memastikan data yang dikirim kembali adalah data fresh dari database
    $product->refresh();

    return response()->json([
        'message' => 'Product updated successfully', 
        'product' => $product
    ]);
}

    public function disableProduct($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->is_available = false;
        $product->save();

        return response()->json(['message' => 'Product disabled successfully', 'product' => $product]);
    }

    public function enableProduct($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->is_available = true;
        $product->save();

        return response()->json(['message' => 'Product enabled successfully', 'product' => $product]);
    }

    public function deleteProduct($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }   

    public function viewCategories()
    {
        $categories = Category::orderBy('name')->get(['id_categories', 'name']);

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    public function addCategory(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Category added successfully', 'category' => $category], 201);
    }

    public function updateCategory(Request $request, $id){
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);

        $category->update($request->only(['name', 'description']));

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }  
    
    public function viewById(Request $request, $id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(['product' => $product]);           
}
}
