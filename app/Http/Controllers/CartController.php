<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = auth()->user();

        // Find or create a cart for the user
        $cart = Cart::create(['user_id' => $user->id]);

        // Validate the request
        $validatedData = $request->validate([
            'medicines' => 'required|array',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            // 'medicines.*.price' => 'required|numeric|min:0',
        ]);

        // Loop through each medicine and add it to the cart
        foreach ($validatedData['medicines'] as $medicineData) {

            $medPrice = Medicine::where('id', $medicineData['id'])->select('price')->first();
            

                // Add new item to the cart
                CartItem::create([
                    'cart_id' => $cart->id,
                    'medicine_id' => $medicineData['id'],
                    'quantity' => $medicineData['quantity'],
                    'price' => $medPrice['price'],
                ]);
        }
        
        return response()->json(['message' => 'Medicines added to cart']);
    }
    public function viewCart()
{
    $user = auth()->user();

    // Check if the user is an admin
    if ($user->role === 'admin') {
        // Retrieve all carts and their items for the admin
        $carts = Cart::with('items')->get();

        foreach ($carts as $cart) {
            foreach ($cart->items as $item) {
                $item->total_price = $item->quantity * $item->medicine->price;
            }
        }

        return response()->json($carts);
    }

    // For regular users, find their cart
    $carts = Cart::where('user_id', $user->id)->with('items')->get();
    foreach ($carts as $cart) {

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart not found or empty'], 404);
        }

        foreach ($cart->items as $item) {
            $item->total_price = $item->quantity * $item->medicine->price;
        }
    

    }

    

    return response()->json($carts);
}

    
    

    

public function removeFromCart($itemId)
{
    // Find the cart item
    $cartItem = CartItem::find($itemId);

    if (!$cartItem) {
        return response()->json(['message' => 'Cart item not found'], 404);
    }

    // Check if the cart item belongs to the user's cart
    $cart = Cart::where('user_id', auth()->id())->first();

    if (!$cart || $cartItem->cart_id !== $cart->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Remove the item from the cart
    $cartItem->delete();

    return response()->json(['message' => 'Cart item removed']);
}

}