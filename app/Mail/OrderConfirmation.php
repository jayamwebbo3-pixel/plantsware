<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $attachInvoice;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $attachInvoice = false)
    {
        $this->order = $order;
        $this->attachInvoice = $attachInvoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
           
            subject: 'Order Confirmation - ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->attachInvoice) {
            try {
                $order = $this->order;
                $order->loadMissing(['items.product', 'items.comboPack', 'couponUsage.coupon']);

                $user = $order->user;
                
                $billingAddress = $order->billing_address;
                if (empty($billingAddress) || !isset($billingAddress['name'])) {
                    $billingAddress = $order->shipping_address;
                }
                
                $shippingAddress = $order->shipping_address;
                if (empty($shippingAddress) || empty($shippingAddress['address'])) {
                    $shippingAddress = $billingAddress;
                }

                $gstSettings = \App\Models\HeaderFooter::first();

                $data = [
                    'invoice_number'   => $order->order_number,
                    'order_date'       => $order->created_at->format('d/M/Y'),
                    'payment_status'   => $order->payment_status,
                    'store_logo'       => asset('assets/images/logo/logo.png'),
                    'store_name'       => $gstSettings->header_title ?? 'Plantsware',
                    'store_address'    => $gstSettings->address ?? 'Plantsware Admin, Tamil Nadu',
                    'store_email'      => $gstSettings->email ?? 'support@plantsware.in',
                    'store_phone'      => $gstSettings->mobile_no ?? '+91 98765 43210',
                    'customer_name'    => ($billingAddress['name'] ?? ($user->name ?? 'Guest')),
                    'customer_email'   => $user->email ?? 'N/A',
                    'customer_phone'   => ($billingAddress['phone'] ?? 'N/A'),
                    'customer_address' => $billingAddress,
                    'shipping_address' => $shippingAddress,
                    'order_items'      => $order->items,
                    'subtotal'         => $order->subtotal,
                    'discount_amount'  => $order->discount,
                    'coupon_code'      => $order->couponUsage && $order->couponUsage->coupon ? $order->couponUsage->coupon->coupon_code : null,
                    'coupon_discount'  => $order->couponUsage ? $order->couponUsage->discount_amount : 0,
                    'shipping_amount'  => $order->shipping,
                    'tax_amount'       => $order->tax,
                    'cgst'             => $order->cgst ?? 0,
                    'sgst'             => $order->sgst ?? 0,
                    'igst'             => $order->igst ?? 0,
                    'grand_total'      => $order->total,
                ];

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order_invoice', $data);

                return [
                    \Illuminate\Mail\Mailables\Attachment::fromData(
                        fn () => $pdf->output(),
                        'Invoice_' . $order->order_number . '.pdf'
                    )->withMime('application/pdf')
                ];
            } catch (\Exception $e) {
                // Return empty if PDF fails so the mail is still sent
            }
        }

        return [];
    }
}
