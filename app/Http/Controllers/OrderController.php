<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Validator;

class OrderController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt.verify');
    }

    /**
     * Get the order of the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $message){
                return response()->json(['message' => $message])->header('code', 400);
            }
        }

        if(!$user = JWTAuth::parseToken()->authenticate()){
            return response()->json(['message' => 'user_not_found'])->header('code', 400);
        }

        $product = Product::find($request->input('product_id'));
        $product_stocks = $product->avalable_stocks;
        $quantity = $request->input('quantity');

        if($quantity > $product_stocks){
            return response()->json(['message' => "Failed to order this product due to unavailability of the stock"])->header('code', 400);
        }

        $product->avalable_stocks = $product_stocks - $quantity;
        $product->save();

        Order::create([
            'user_id' => $user->id,
            'product_id' => $request->input('product_id'),
            'quantity' => $quantity
        ]);

        return response()->json(['message' => "You have successfully ordered this product."])->header('code', 201);
    }
}
