<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $product = Product::findOrFail($productId);

        if ($product->available_stock >= $quantity) {
            $product->available_stock -= $quantity;
            $product->save();

            $order = new Order([
                'user_id' => Auth::id(),
                'quantity' => $quantity,
            ]);

            $product->orders()->save($order);

            return response(['message' => 'You have successfully ordered this product.'], 201);
        } else {
            return response(['message' => 'Failed to order this product due to unavailability of the stock'], 400);
        }
    }
}
