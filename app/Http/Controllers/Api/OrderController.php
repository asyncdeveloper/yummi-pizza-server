<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => [
                'required',
                'min:1',
                'array',
                function($attribute, $value, $fail) {
                    $ids = array_keys($value);
                    $menuCountWithinArrIDs = Menu::whereIn('id', $ids)->count();
                    if ($menuCountWithinArrIDs != count($ids))
                        return $fail($attribute.' is invalid.');

                    return true;
                }
            ],
            'products.*' => 'required|numeric|min:1',
            'name' => 'required|string|min:3|max:191',
            'address' => 'required|string|min:3|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->errors() ], 422);
        }

        $data = $validator->validated();

        $productIds = $data['products'];
        $products = collect([]);
        $total = 0;
        foreach ($productIds as $product => $quantity) {
            $item = Menu::find($product);
            $total += $item->price * $quantity;
            $products->push($item->only([ 'id', 'name', 'price' ]));
        }

        $total+=  $total * 0.05; // 5 % of total price is delivery cost;

        Order::create([
            'products' => json_encode($products),
            'name' => $data['name'],
            'address' => $data['address'],
            'total_cost' => $total
        ]);

        return response()->json([ 'message' => 'Order created successfully' ], 201);
    }
}
