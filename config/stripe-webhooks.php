<?php

return [

    /*
     * Stripe will sign each webhook using a secret. You can find the used secret at the
     * webhook configuration settings: https://dashboard.stripe.com/account/webhooks.
     */
    'signing_secret' => env('STRIPE_WH'),

    /*
     * You can define the job that should be run when a certain webhook hits your application
     * here. The key is the name of the Stripe event type with the `.` replaced by a `_`.
     *
     * You can find a list of Stripe webhook types here:
     * https://stripe.com/docs/api#event_types.
     */
    'jobs' => [
        'charge_succeeded'=> \App\Jobs\StripeWebhooks\ChargeSucceededJob::class,
        'customer_subscription_updated'=>\App\Jobs\StripeWebhooks\CustomerSubscriptionUpdatedJob::class,
        'checkout_session_completed'=>\App\Jobs\StripeWebhooks\CheckoutSessionCompletedJob::class,
        'customer_subscription_deleted'=>\App\Jobs\StripeWebhooks\CustomerSubscriptionDeletedJob::class,
        'customer_subscription_created'=>\App\Jobs\StripeWebhooks\CustomerSubscriptionCreatedJob::class,
        'invoice_payment_succeeded'=>\App\Jobs\StripeWebhooks\InvoicePaymentSucceededJob::class,
        // 'source_chargeable' => \App\Jobs\StripeWebhooks\HandleChargeableSource::class,
        // 'charge_failed' => \App\Jobs\StripeWebhooks\HandleFailedCharge::class,
    ],

    /*
     * The classname of the model to be used. The class should equal or extend
     * Spatie\StripeWebhooks\ProcessStripeWebhookJob.
     */
    'model' => \Spatie\StripeWebhooks\ProcessStripeWebhookJob::class,
];
