# E-Commerce Payment & Order Workflow (Online Payment Only)

This e-commerce system follows a strict payment-first approach, where an order is created only after a successful online payment. Cash on Delivery (COD) is not supported. To ensure data accuracy, avoid fake orders, and handle delayed payment gateways, a 10-minute pending payment window is implemented using a CRON job.

## Payment & Order Creation Rule

*   An order is created **only if the payment status becomes SUCCESS** within 10 minutes of payment initiation.
*   If the payment fails or remains pending beyond 10 minutes, the payment is automatically expired and no order is created.

## Payment Status Flow

When a user clicks “Pay Now”, a payment record is created with the status `INITIATED`, and the user is redirected to the payment gateway.
Once the gateway starts processing, the payment status becomes `PENDING`.

At this stage:
*   No order is created
*   No stock is reduced
*   The system waits for a gateway callback

The payment can then move to one of the following states:
*   `SUCCESS` → Payment completed within 10 minutes
*   `FAILED` → Payment failed at any time
*   `EXPIRED` → Payment still pending after 10 minutes (handled by CRON)

## Order Creation Logic

If the gateway callback returns `SUCCESS` within 10 minutes, the system immediately:
1.  Creates the order
2.  Sets `order_status = CONFIRMED`
3.  Stores `payment_status = SUCCESS`

This is the **only point** where an order is created.

If the payment status is `FAILED`, the payment record is closed or removed, and no order is created.

If the payment remains `PENDING` for more than 10 minutes, a CRON job marks the payment as `EXPIRED` and removes it. Any late success callback after expiry is ignored.

## CRON Job Handling (10-Minute Rule)

A scheduled CRON job runs at regular intervals (recommended every 1 minute) and checks for pending payments.

**If:**
*   `payment_status = PENDING`
*   and `created_at > 10 minutes`

**Then:**
*   The payment is marked as `EXPIRED`
*   The payment record is cleaned up
*   Order creation is permanently blocked for that payment

This ensures abandoned or stuck payments do not pollute the system.

## Order Fulfillment Flow (After Payment Success Only)

Once an order is confirmed, it follows the normal fulfillment lifecycle controlled by the admin:

`CONFIRMED` → `PROCESSING` → `SHIPPED` → `DELIVERED`

Only orders with successful payments enter this flow. Unpaid, failed, or expired payments never appear in the admin order panel.

## Key Benefits of This Workflow

*   Prevents fake or unpaid orders
*   Handles delayed payment gateway responses safely
*   Ensures clean order and payment data
*   Fully automated cleanup using CRON
*   Industry-standard and production-ready

### One-Line Summary

**Payment SUCCESS within 10 minutes creates an order; otherwise, the payment expires and no order is created.**