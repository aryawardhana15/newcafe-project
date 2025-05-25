<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $title = "Product";
        $products = Product::all();

        return view('/product/index', compact("title", "products"));
    }

    
    
    public function getProductData($id)
    {
        $product = Product::find($id);

        return $product;
    }


    public function addProductGet()
    {
        $title = "Add Product";

        return view('/product/add_product', compact("title"));
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
    
        if ($product->image) {
            Storage::delete($product->image);
        }
    
        $product->delete();
    
        return back()->with('success', 'Produk berhasil dihapus');
    }
    
    
    public function addProductPost(Request $request)
    {
        $validatedData = $request->validate([
            "product_name" => "required|max:25",
            "stock" => "required|numeric|gt:0",
            "price" => "required|numeric|gt:0",
            "description" => "required",
            "category_id" => "required|exists:categories,id",
            "image" => "image|max:2048"
        ]);

        if (!isset($validatedData["image"])) {
            $validatedData["image"] = env("IMAGE_PRODUCT");
        } else {
            $validatedData["image"] = $request->file("image")->store("product");
        }

        try {
            $validatedData['user_id'] = auth()->id();
            $validatedData['is_available'] = true;
            
            Product::create($validatedData);
            return redirect('/product')->with('success', 'Product has been added!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add product. Please try again.');
        }
    }


    public function editProductGet(Product $product)
    {
        $data["title"] = "Edit Product";
        $data["product"] = $product;

        return view("/product/edit_product", $data);
    }


    public function editProductPost(Request $request, Product $product)
    {
        $rules = [
            'description' => 'required',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|numeric|gt:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|file|max:2048'
        ];

        if ($product->product_name != $request->product_name) {
            $rules['product_name'] = 'required|max:25|unique:products,product_name';
        } else {
            $rules['product_name'] = 'required|max:25';
        }

        $validatedData = $request->validate($rules);

        try {
            if ($request->file("image")) {
                if ($request->oldImage != env("IMAGE_PRODUCT")) {
                    Storage::delete($request->oldImage);
                }
                $validatedData["image"] = $request->file("image")->store("product");
            }

            $product->fill($validatedData);

            if ($product->isDirty()) {
                $product->save();
                return redirect("/product")->with('success', 'Product has been updated!');
            } else {
                return back()->with('info', 'No changes detected.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update product. Please try again.');
        }
    }
}
