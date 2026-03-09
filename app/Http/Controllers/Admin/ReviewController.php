<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\ComboPack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with(['user', 'product', 'comboPack']);
        $filterName = null;
        $backUrl = route('admin.dashboard');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
            $product = Product::with('subcategory')->find($request->product_id);
            if ($product) {
                $filterName = 'Product: ' . $product->name;
                $backUrl = route('admin.subcategories.products', $product->subcategory_id);
            }
        } elseif ($request->filled('combo_pack_id')) {
            $query->where('combo_pack_id', $request->combo_pack_id);
            $combo = ComboPack::find($request->combo_pack_id);
            if ($combo) {
                $filterName = 'Combo: ' . $combo->name;
                $backUrl = route('admin.combo-packs.index');
            }
        }

        $reviews = $query->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews', 'filterName', 'backUrl'));
    }

    public function toggleApproval($id)
    {
        try {
            DB::beginTransaction();

            $review = ProductReview::findOrFail($id);
            $review->is_approved = !$review->is_approved;
            $review->save();

            // Update stats
            if ($review->product_id) {
                $this->updateRatingStats(Product::class, $review->product_id);
            } elseif ($review->combo_pack_id) {
                $this->updateRatingStats(ComboPack::class, $review->combo_pack_id);
            }

            DB::commit();
            return back()->with('success', 'Review status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating review: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $review = ProductReview::findOrFail($id);
            $productId = $review->product_id;
            $comboPackId = $review->combo_pack_id;

            $review->delete();

            // Update stats
            if ($productId) {
                $this->updateRatingStats(Product::class, $productId);
            } elseif ($comboPackId) {
                $this->updateRatingStats(ComboPack::class, $comboPackId);
            }

            DB::commit();
            return back()->with('success', 'Review deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting review: ' . $e->getMessage());
        }
    }

    protected function updateRatingStats($modelClass, $id)
    {
        $stats = ProductReview::where(
            $modelClass === Product::class ? 'product_id' : 'combo_pack_id',
            $id
        )
            ->where('is_approved', true)
            ->selectRaw('count(*) as total, avg(rating) as average')
            ->first();

        $model = $modelClass::find($id);
        if ($model) {
            $model->avg_rating = $stats->average ?? 0;
            $model->total_reviews = $stats->total ?? 0;
            $model->save();
        }
    }
}
