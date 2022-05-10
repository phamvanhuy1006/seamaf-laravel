<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use App\Models\Image;
use App\Http\Resources\ProductsResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
      
        foreach ($products as $product)
        {
            // dd($product->images);

            $product->images;
        }
        $ngocngu = [];
        for ($i = 0; $i < count($products); $i++) {
            $ngocngu[] = $i;
            // array_push($ngocngu, $products[$i])
        }
        // echo json_encode($ngocngu);
        unset($i);
        echo $i;
        // return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->category;
        $product->images;

        return response()->json($product);
    }

    public function relateProducts(Request $request) {
        $relateProducts = Product::where('category_id', '=', $request->categoryId)->get();

        foreach ($relateProducts as $product)
        {
            $product->images;
        }

        return response()->json($request->categoryId);
    }

    public function lastestProducts()
    {
        $products = Product::orderBy('created_at', 'DESC')->take(20)->get();

        foreach ($products as $product)
        {
            $product->images;
        }

        return response()->json($products);
    }

    public function topSellProducts()   
    {
        $products = Product::where('is_top', '1')->take(10)->get();

        foreach ($products as $product)
        {
            $product->images;
        }

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $products = Product::search($request->keyword)->get();

        foreach ($products as $product)
        {
            $product->images;
        }

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $image = $this->getFileName($request['image']);

            $product = Product::create([
                'name' => $request['name'],
                'code' => $request['code'],
                'description' => $request['description'],
                'category_id' => $request['category_id'],
                'price' => $request['price'],
            ]);

            Image::create([
                'product_id' => $product->id,
                'path' => $image
            ]);
            DB::commit();
            return response()->json([
                'data' => $product,
                'status' => '200',
                'message' => 'Success'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getFileName($image)
    {
        if ($image != null) {
            $image->move(storage_path('app/public/assets/products/'), $image->getClientOriginalName());

            $path = 'storage/assets/products/'. $image->getClientOriginalName();
            
            return $path;
        } else {
            return "storage/assets/products/giay4.png";
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function onTop(Product $product)
    {
        $product->update([
            'is_top' => 1
        ]);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (isset($product->images[0])) {
            $product->images[0]->delete();
        }

        Cart::where('product_id', $product->id)->delete();

        $product->delete();


        return response()->json([
            'data' => $product,
            'status' => '200',
            'message' => 'Delete'
        ]);
    }
}
