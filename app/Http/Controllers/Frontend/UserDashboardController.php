<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('view.userdashboard', [
            'user' => $user,
            'addresses' => $user->addresses,
            'orders' => $user->orders()->latest()->get(),
            'wishlist' => $user->wishlist()->with('product')->get(),
        ]);
    }

    public function cancelOrder($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order has been successfully cancelled.');
    }
    public function returnOrder(\Illuminate\Http\Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Only delivered orders can be returned.');
        }

        if (!$order->delivered_at || $order->delivered_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Return window (24 hours) has expired.');
        }

        $request->validate([
            'reason' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('return_images', 'public');
            }
        }

        $order->update([
            'status' => 'return_requested',
            'return_requested_at' => now(),
            'return_reason' => $request->reason,
            'return_images' => $imagePaths
        ]);

        return back()->with('success', 'Return request submitted successfully.');
    }

    public function getOrderItems($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('items')->findOrFail($id);

        return response()->json([
            'items' => $order->items->map(function ($item) use ($user, $order) {
                $review = \App\Models\ProductReview::where('user_id', $user->id)
                    ->where('order_id', $order->id)
                    ->where(function ($query) use ($item) {
                        if ($item->product_id) {
                            $query->where('product_id', $item->product_id);
                        } else {
                            $query->where('combo_pack_id', $item->combo_pack_id);
                        }
                    })->first();

                $isEditable = true;
                if ($review) {
                    $isEditable = $review->updated_at->diffInDays(now()) <= 30;
                }

                return [
                    'id' => $item->id,
                    'name' => $item->product_name,
                    'product_id' => $item->product_id,
                    'combo_pack_id' => $item->combo_pack_id,
                    'existing_rating' => $review ? $review->rating : null,
                    'existing_review' => $review ? $review->review : null,
                    'is_editable' => $isEditable,
                ];
            })
        ]);
    }
}
