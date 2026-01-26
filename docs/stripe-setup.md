# Stripe Setup

This project uses Stripe Checkout for one-off plan purchases and a webhook to activate subscriptions.

## Environment

Add these values to `.env` (placeholders are in `.env.example`):

- `STRIPE_KEY` (Publishable key)
- `STRIPE_SECRET` (Secret key)
- `STRIPE_WEBHOOK_SECRET` (Signing secret for the webhook endpoint)

Make sure `APP_URL` matches the production domain so the checkout return URLs are correct.

## Webhook

Create a Stripe webhook endpoint:

- URL: `https://your-domain/stripe/webhook`
- Events: `checkout.session.completed`

Copy the signing secret into `STRIPE_WEBHOOK_SECRET`.

## Notes

- Checkout session creation is handled in `app/Services/StripeCheckoutService.php`.
- Webhook handling is in `app/Http/Controllers/StripeWebhookController.php`.
