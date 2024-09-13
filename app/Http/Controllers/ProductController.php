<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view("products.index");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name"          => "required|max:255",
            "slug"          => "required|max:5|min:3|unique:products",
            "description"   => "required",
            "price"         => "required|numeric",
        ],[
            "name.required"         => "Nama tidak boleh kosong",
            "name.max"              => "Batas maksimal nama adalah 255 karakter",
            "slug.required"         => "Slug tidak boleh kosong",
            "slug.min"              => "Slug minimal 3 karakter",
            "slug.max"              => "Batas maksimal slug adalah 5 karakter",
            "slug.unique"           => "Slug sudah dipakai",
            "description.required"  => "Deskripsi tidak boleh kosong",
            "price.required"        => "Harga tidak boleh kosong",
            "price.numeric"         => "Harga hanya boleh diisi angka",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $save = Product::create([
            "name"          => $request->name,
            "slug"          => $request->slug,
            "description"   => $request->description,
            "price"         => $request->price
        ]);

        return response()->json([
            "statusCode"    => 200,
            "message"       => "Data saved successfully",
            "data"          => $save,
            "number"        => count(Product::get())
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Product::find($id);
        if(!$data){
            return response()->json([
                "statusCode"    => 404,
                "message"       => "Data not found"
            ], 200);
        }
        return response()->json([
            "statusCode"    => 200,
            "message"       => "Success",
            "data"          => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            "name"          => "required|max:255",
            "slug"          => "required|min:3|max:5",
            "description"   => "required",
            "price"         => "required|numeric"
        ],
        [
            "name.required"         =>  "Nama tidak boleh kosong",
            "name.max"              =>  "Nama maksimal 255 karakter",
            "slug.required"         =>  "Slug tidak boleh kosong",
            "slug.min"              =>  "Slug minimal 3 karakter",
            "slug.max"              =>  "Slug maksimal 5 karakter",
            "description.required"  =>  "Deskripsi tidak boleh kosong",
            "price.required"        =>  "Price tidak boleh kosong",
            "Price.numeric"         =>  "Price harus numeric"
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);
        if(!$product){
            return response()->json([
                "statusCode"    => 404,
                "message"       => "Data not found"
            ]);
        }
        $product->name          = $request->name;
        $product->slug          = $request->slug;
        $product->description   = $request->description;
        $product->price         = $request->price;
        $product->save();
        return response()->json([
            "statusCode"    =>  200,
            "message"       =>  "Success",
            "data"          =>  $product,
            "number"        =>  count(Product::get())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return ressponse()->json([
                "statusCode"    => 404,
                "message"       => "Data not found"
            ]);
        }
        $product->delete();
        return response()->json([
            "statusCode"    => 200,
            "message"       => "Successfully delete data"
        ]);
    }

    public function renderDataServerside(Request $request)
    {
        $product = Product::get();
        return DataTables::of($product)
                        ->addIndexColumn()
                        ->addColumn('Action', function($row) {
                            return '
                                <div>
                                    <button class="btn btn-md btn-warning" data-id="'. $row->id .'">Edit</button>
                                    <button class="btn btn-md btn-danger" data-id="'. $row->id .'">Hapus</button>
                                </div>
                            ';
                        })
                        ->rawColumns(['Action'])
                        ->toJson();
    }
}
