<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Medicine;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = auth()->user();

        // Find or create a cart for the user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Validate the request
        $validatedData = $request->validate([
            'medicines' => 'required|array',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
        ]);

        // Loop through each medicine and add it to the cart
        foreach ($validatedData['medicines'] as $medicineData) {
            // Check if the item already exists in the cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('medicine_id', $medicineData['id'])
                                ->first();

            if ($cartItem) {
                // Update quantity if item already exists
                $cartItem->quantity += $medicineData['quantity'];
                $cartItem->save();
            } else {
                // Add new item to the cart
                CartItem::create([
                    'cart_id' => $cart->id,
                    'medicine_id' => $medicineData['id'],
                    'quantity' => $medicineData['quantity'],
                    'price' => $medicineData['price'],
                ]);
            }
        }
        
        return response()->json(['message' => 'Medicines added to cart']);
    }
    public function viewCart()
    {
        $user = auth()->user();
    
        // Check if the user is an admin
        if ($user->role === 'admin') {
            // Retrieve all carts and their items for the admin
            $carts = Cart::with('items.medicine')->get();
            return response()->json($carts);
        }
    
        // For regular users, find their cart
        $cart = Cart::where('user_id', $user->id)->with('items.medicine')->get();
    
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
    
        return response()->json($cart);
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