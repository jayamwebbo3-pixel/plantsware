<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $coupon;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Coupon $coupon, User $user)
    {
        $this->coupon = $coupon;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Special Coupon for You! - ' . $this->coupon->coupon_code)
            ->view('emails.coupon_assigned');
    }
}
