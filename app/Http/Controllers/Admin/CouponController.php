<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Mail\CouponAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::withCount('users')
            ->latest()
            ->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|unique:coupons,coupon_code|max:50',
            'discount_type' => 'required|string|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'required|numeric|min:0',
            'validity_range' => 'nullable|string',
            'status' => 'required|boolean',
            'audience_type' => 'required|string|in:all,specific,value,top',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'target_min_purchase' => 'nullable|numeric|min:0',
            'target_top_count' => 'nullable|integer|min:1',
        ]);

        $data = $request->only([
            'coupon_code',
            'discount_type',
            'discount_value',
            'max_discount',
            'minimum_order_amount',
            'status'
        ]);

        // Parse validity range
        $this->parseValidityRange($request->validity_range, $data);

        $data['coupon_code'] = strtoupper($data['coupon_code']);
        $data['is_public'] = $request->audience_type === 'all';

        // Create coupon
        $coupon = Coupon::create($data);

        // Determine target users to assign
        $targetUserIds = $this->determineTargetUserIds($request);

        if (!$data['is_public'] && !empty($targetUserIds)) {
            $coupon->users()->sync($targetUserIds);

            // Send emails to assigned users
            $this->sendCouponEmails($coupon, $targetUserIds);
        } elseif ($data['is_public']) {
            // Send emails to all users registered in the current month and past 5 months
            $allUserIds = User::where('created_at', '>=', now()->subMonths(5)->startOfMonth())->pluck('id')->toArray();
            $this->sendCouponEmails($coupon, $allUserIds);
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $assignedUsers = $coupon->users;
        return view('admin.coupons.edit', compact('coupon', 'assignedUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50|unique:coupons,coupon_code,' . $coupon->id,
            'discount_type' => 'required|string|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'required|numeric|min:0',
            'validity_range' => 'nullable|string',
            'status' => 'required|boolean',
            'audience_type' => 'required|string|in:all,specific,value,top',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'target_min_purchase' => 'nullable|numeric|min:0',
            'target_top_count' => 'nullable|integer|min:1',
        ]);

        $data = $request->only([
            'coupon_code',
            'discount_type',
            'discount_value',
            'max_discount',
            'minimum_order_amount',
            'status'
        ]);

        // Parse validity range
        $this->parseValidityRange($request->validity_range, $data);

        $data['coupon_code'] = strtoupper($data['coupon_code']);
        $data['is_public'] = $request->audience_type === 'all';

        // Get currently assigned user IDs before updates
        $previousUserIds = $coupon->users()->pluck('users.id')->toArray();

        $coupon->update($data);

        // Determine target users to assign
        $targetUserIds = $this->determineTargetUserIds($request);

        if ($data['is_public']) {
            $coupon->users()->detach();
            
            // If it transitioned from private to public, notify all users registered in current month and past 5 months
            if (!$coupon->getOriginal('is_public')) {
                $allUserIds = User::where('created_at', '>=', now()->subMonths(5)->startOfMonth())->pluck('id')->toArray();
                $this->sendCouponEmails($coupon, $allUserIds);
            }
        } else {
            $coupon->users()->sync($targetUserIds);

            // Send emails ONLY to newly added users
            $newlyAddedUserIds = array_diff($targetUserIds, $previousUserIds);
            if (!empty($newlyAddedUserIds)) {
                $this->sendCouponEmails($coupon, $newlyAddedUserIds);
            }
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    /**
     * AJAX action to search users for coupon targeting.
     */
    public function searchUsers(Request $request)
    {
        $type = $request->type;

        if ($type === 'specific') {
            $query = $request->input('query');
            $users = User::where('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$query}%")
                ->limit(50)
                ->get(['id', 'name', 'email', 'phone']);

            return response()->json(['users' => $users]);
        }

        if ($type === 'above_value') {
            $value = (float) $request->input('value', 0);
            $count = User::where('total_purchase_value', '>=', $value)->count();

            return response()->json(['count' => $count]);
        }

        if ($type === 'top_n') {
            $n = (int) $request->input('value', 0);
            $userCount = User::count();
            $count = min($n, $userCount);

            return response()->json(['count' => $count]);
        }

        return response()->json(['error' => 'Invalid search type'], 400);
    }

    /**
     * Get detailed coupon usage and audience report.
     */
    public function report(Coupon $coupon)
    {
        if ($coupon->is_public) {
            $targetedUsers = User::where('created_at', '>=', now()->subMonths(5)->startOfMonth())->get(['users.id', 'users.name', 'users.email', 'users.phone']);
        } else {
            $targetedUsers = $coupon->users()->get(['users.id', 'users.name', 'users.email', 'users.phone']);
        }
        $usages = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
            ->with(['user:id,name,email,phone', 'order:id,order_number'])
            ->get();

        $usersList = [];
        $processedUserIds = [];

        // 1. Add all targeted users
        foreach ($targetedUsers as $user) {
            $usage = $usages->firstWhere('user_id', $user->id);
            $usersList[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_targeted' => true,
                'has_used' => $usage ? true : false,
                'order_number' => $usage && $usage->order ? $usage->order->order_number : null,
                'discount_amount' => $usage ? number_format($usage->discount_amount, 2) : '0.00',
                'used_at' => $usage ? $usage->used_at->format('d M Y H:i') : null,
            ];
            $processedUserIds[] = $user->id;
        }

        // 2. Add any other users who used it but weren't targeted
        foreach ($usages as $usage) {
            if (in_array($usage->user_id, $processedUserIds)) {
                continue;
            }
            $user = $usage->user;
            if ($user) {
                $usersList[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'is_targeted' => false,
                    'has_used' => true,
                    'order_number' => $usage->order ? $usage->order->order_number : null,
                    'discount_amount' => number_format($usage->discount_amount, 2),
                    'used_at' => $usage->used_at->format('d M Y H:i'),
                ];
            }
        }

        // Coupon details summary
        $details = [
            'coupon_code' => $coupon->coupon_code,
            'discount_text' => $coupon->discount_type === 'percentage' 
                ? number_format($coupon->discount_value, 0) . '%' . ($coupon->max_discount ? ' (Max ₹' . number_format($coupon->max_discount, 2) . ')' : '')
                : '₹' . number_format($coupon->discount_value, 2),
            'min_order' => '₹' . number_format($coupon->minimum_order_amount, 2),
            'validity' => ($coupon->valid_from || $coupon->valid_to)
                ? 'From: ' . ($coupon->valid_from ? $coupon->valid_from->format('d M Y') : 'N/A') . ' To: ' . ($coupon->valid_to ? $coupon->valid_to->format('d M Y') : 'N/A')
                : 'No limit',
            'audience_text' => $coupon->is_public ? 'All Customers - Current & Past 5 Months' : 'Restricted (Targeted)',
            'status_text' => $coupon->status ? 'Active' : 'Inactive',
            'total_usages' => $usages->count(),
            'total_discount_given' => '₹' . number_format($usages->sum('discount_amount'), 2),
        ];

        return response()->json([
            'coupon' => $details,
            'users' => $usersList,
        ]);
    }

    /**
     * Helper to parse validity range.
     */
    private function parseValidityRange($rangeStr, &$data)
    {
        if (empty($rangeStr)) {
            $data['valid_from'] = null;
            $data['valid_to'] = null;
            return;
        }

        $dates = explode(' to ', $rangeStr);
        if (count($dates) === 2) {
            $data['valid_from'] = trim($dates[0]);
            $data['valid_to'] = trim($dates[1]);
        } else {
            $data['valid_from'] = trim($dates[0]);
            $data['valid_to'] = trim($dates[0]);
        }
    }

    /**
     * Helper to determine user IDs from targeting fields.
     */
    private function determineTargetUserIds(Request $request)
    {
        $audience = $request->audience_type;

        if ($audience === 'specific') {
            return $request->input('user_ids', []);
        }

        if ($audience === 'value') {
            $minVal = (float) $request->input('target_min_purchase', 0);
            return User::where('total_purchase_value', '>=', $minVal)->pluck('id')->toArray();
        }

        if ($audience === 'top') {
            $limit = (int) $request->input('target_top_count', 0);
            if ($limit < 1) return [];
            return User::orderBy('total_purchase_value', 'desc')->limit($limit)->pluck('id')->toArray();
        }

        return [];
    }

    /**
     * Send email notifications.
     */
    private function sendCouponEmails(Coupon $coupon, array $userIds)
    {
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new CouponAssigned($coupon, $user));
            } catch (\Exception $e) {
                Log::error("Failed to send coupon email to User ID {$user->id} ({$user->email}): " . $e->getMessage());
            }
        }
    }
}
