<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'value' => $request->input('value'),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'value' => $rating->value,
            ]);
        }

        return redirect()->back()->with('success', 'Your rating has been saved!');
    }

    public function destroy($productId, Request $request)
    {
        $user = \Auth::user();
        $rating = \App\Models\Rating::where('user_id', $user->id)->where('product_id', $productId)->first();
        if ($rating) {
            $rating->delete();
        }
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Your rating has been removed.');
    }
}
