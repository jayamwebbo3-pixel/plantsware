<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\Product;
use App\Models\ComboPack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:product,combo',
            'items.*.rating' => 'required|integer|min:1|max:5',
            'items.*.review' => 'nullable|string',
        ]);

        $user = Auth::user();
        $order = $user->orders()->findOrFail($request->order_id);

        if (!in_array(strtolower($order->status), ['delivered', 'completed'])) {
            return back()->with('error', 'You can only review delivered orders.');
        }

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                $reviewData = [
                    'rating' => $item['rating'],
                    'review' => $item['review'],
                    'is_approved' => true, // Auto-approving for now
                ];

                $searchCriteria = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                ];

                if ($item['type'] === 'product') {
                    $searchCriteria['product_id'] = $item['id'];
                    $searchCriteria['combo_pack_id'] = null;
                } else {
                    $searchCriteria['combo_pack_id'] = $item['id'];
                    $searchCriteria['product_id'] = null;
                }

                $existingReview = ProductReview::where($searchCriteria)->first();
                if ($existingReview && $existingReview->updated_at->diffInDays(now()) > 30) {
                    continue; // Skip items that are no longer editable
                }

                ProductReview::updateOrCreate($searchCriteria, $reviewData);

                // Update Product/Combo Rating Stats
                $this->updateRatingStats($item['id'], $item['type']);
            }

            DB::commit();
            return back()->with('success', 'Thank you for your review!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while saving your review: ' . $e->getMessage());
        }
    }

    private function updateRatingStats($id, $type)
    {
        if ($type === 'product') {
            $model = Product::find($id);
            $reviews = ProductReview::where('product_id', $id)->where('is_approved', true);
        } else {
            $model = ComboPack::find($id);
            $reviews = ProductReview::where('combo_pack_id', $id)->where('is_approved', true);
        }

        if ($model) {
            $model->avg_rating = $reviews->avg('rating') ?: 0;
            $model->total_reviews = $reviews->count();
            $model->save();
        }
    }
}
