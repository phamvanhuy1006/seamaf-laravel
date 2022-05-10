<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    public function store(Request $request)
    {
       $cart = [
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'quality' => $request->quality
       ];

       if (Cart::check($cart)->count() == 0) {
            return response()->json(Cart::create($cart));
       } else {
           return false;
       }
    }

    public function list(Request $request)
    {
        $carts = Cart::where('user_id', $request->user_id)->get();

        if (isset($carts)) {
            foreach ($carts as $cart) {
                $product = Product::where('id', $cart->product_id)->first();
                
                if(isset($product->images[0])) { $cart['image'] = $product->images[0]->path; }
                $cart['price'] = $product->price;
                $cart['name'] = $product->name;
            }

            return response()->json($carts);
       }
    }

    public function updateQuality(Request $request)
    {
        $cart = Cart::findOrFail($request->cart_id);

        $cart->update([
            'quality' => $request->quality,
        ]);

        $cart->save();

        return response()->json($cart);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return "success";
    }
}
