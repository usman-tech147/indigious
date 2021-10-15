<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Lcobucci\JWT\Token\Plain;
use PayPal\Api\Plan;
use \PayPal\Rest\ApiContext;
use \PayPal\Auth\OAuthTokenCredential;

class PayPalController extends Controller
{
    public $context;

    public static function getApiContext()
    {
        return $apiContext = new ApiContext(
            new OAuthTokenCredential(
                env('PAYPAL_CLIENT_ID'),
                env('PAYPAL_SECRET')
            )
        );
    }

    public static function getToken()
    {
        $response = Http::withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'))->asForm()->
        post(env('PAYPAL_MODE') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);
        $responseDecode = json_decode($response);
        return $token = $responseDecode->token_type . ' ' . $responseDecode->access_token;
    }

    public static function createProduct($name, $description, $imageURL)
    {

        return json_decode(Http::
        withHeaders(
            [
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
                "PayPal-Request-Id" => "PRODUCT-" . uniqid() . time() . date('d-m-y')
            ])
            ->post(env('PAYPAL_MODE') . '/v1/catalogs/products',
                [
                    'name' => $name,
                    'description' => substr($description, 0, 127),
                    'type' => 'SERVICE',
                    'category' => 'SOFTWARE',
                    'image_url' => $imageURL,
                    'home_url' => env('APP_URL'),
                ]
            ));
    }

    public static function createPlan($productId, $planName, $interval, $intervalCount, $price)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
                "PayPal-Request-Id" => "PLAN-" . uniqid() . time() . date('d-m-y')
            ])
            ->post(env('PAYPAL_MODE') . '/v1/billing/plans',
                array(
                    'product_id' => $productId,
                    'name' => $planName,
                    'description' => "$interval $planName For $price",
                    'status' => 'Active',
                    'billing_cycles' =>
                        array(
                            0 =>
                                array(
                                    'frequency' =>
                                        array(
                                            'interval_unit' => strtoupper($interval),
                                            'interval_count' => $intervalCount,
                                        ),
                                    'tenure_type' => 'REGULAR',
                                    'sequence' => 1,
                                    'total_cycles' => 0,
                                    'pricing_scheme' =>
                                        array(
                                            'fixed_price' =>
                                                array(
                                                    'value' => $price,
                                                    'currency_code' => 'USD',
                                                ),
                                        ),
                                ),
                        ),
                    'payment_preferences' =>
                        array(
                            'auto_bill_outstanding' => false,
                            'setup_fee' =>
                                array(
                                    'value' => '0',
                                    'currency_code' => 'USD',
                                ),
                            'setup_fee_failure_action' => 'CANCEL',
                            'payment_failure_threshold' => 1,
                        ),
                    'taxes' =>
                        array(
                            'percentage' => '0',
                            'inclusive' => false,
                        ),
                )
            ));
    }

    public static function subscribeNow($planId, $successURL, $cancelURL)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
                "PayPal-Request-Id" => "SUBSCRIPTION-" . uniqid() . time() . date('d-m-y')
            ])
            ->post(env('PAYPAL_MODE') . '/v1/billing/subscriptions',
                array(
                    'plan_id' => $planId,
                    'start_time' => Carbon::now()->addSeconds(5),
                    'quantity' => '1',
                'shipping_amount' =>
                    array (
                        'currency_code' => 'USD',
                        'value' => '0',
                    ),
                    'subscriber' =>
                        array(
                            'name' =>
                                array(
                                    'given_name' => 'John',
                                    'surname' => 'Doe',
                                ),
                            'email_address' => 'customer@example.com',
                            'shipping_address' =>
                                array(
                                    'name' =>
                                        array(
                                            'full_name' => 'John Doe',
                                        ),
                                    'address' =>
                                        array(
                                            'address_line_1' => '2211 N First Street',
                                            'address_line_2' => 'Building 17',
                                            'admin_area_2' => 'San Jose',
                                            'admin_area_1' => 'CA',
                                            'postal_code' => '95131',
                                            'country_code' => 'US',
                                        ),
                                ),
                        ),
                    'application_context' =>
                        array(
                            'brand_name' => 'Indigenous Lifestyle',
                            'locale' => 'en-US',
                            'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                            'user_action' => 'SUBSCRIBE_NOW',
                            'payment_method' =>
                                array(
                                    'payer_selected' => 'PAYPAL',
                                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                                ),
                            'return_url' => $successURL,
                            'cancel_url' => $cancelURL,
                        ),
                )
            ));
    }

    public static function subscriptionDetails($agreementId)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->get(env('PAYPAL_MODE') . '/v1/billing/subscriptions/' . $agreementId));
    }

    public static function productDetails($productId)
    {

        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->get(env('PAYPAL_MODE') . "/v1/catalogs/products/$productId"));
    }

    public static function planDetails($planId)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->get(env('PAYPAL_MODE') . "/v1/billing/plans/$planId"));
    }

    public static function updatePrice($planId, $price)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->post(env('PAYPAL_MODE') . "/v1/billing/plans/$planId/update-pricing-schemes",
                array(
                    'pricing_schemes' =>
                        array(
                            0 =>
                                array(
                                    'billing_cycle_sequence' => 1,
                                    'pricing_scheme' =>
                                        array(
                                            'fixed_price' =>
                                                array(
                                                    'value' => $price,
                                                    'currency_code' => 'USD',
                                                ),
                                        ),
                                ),
                        ),
                )
            )
        );
    }

    public static function cancelSubscription($subscriptionId, $reason)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->post(env('PAYPAL_MODE') . "/v1/billing/subscriptions/$subscriptionId/cancel",
                [
                    "reason" => "$reason"
                ]
            )
        );
    }

    public static function suspendSubscription($subscriptionId, $reason)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->post(env('PAYPAL_MODE') . "/v1/billing/subscriptions/$subscriptionId/suspend",
                [
                    "reason" => "$reason"
                ]
            )
        );
    }

    public static function activateSubscription($subscriptionId, $reason)
    {
        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->post(env('PAYPAL_MODE') . "/v1/billing/subscriptions/$subscriptionId/activate",
                [
                    "reason" => "$reason"
                ]
            )
        );
    }

    public static function reviseSubscriptionPlan($subscriptionId, $newPlan, $successURL, $cancelURL)
    {

        return json_decode(Http::
        withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => self::getToken(),
                "Content-Type" => "application/json",
            ])
            ->post(env('PAYPAL_MODE') . " /v1/billing/subscriptions/$subscriptionId/revise",
                array(
                    'plan_id' => $newPlan,
                    'shipping_amount' =>
                        array(
                            'currency_code' => 'USD',
                            'value' => '0',
                        ),
//                    'shipping_address' =>
//                        array (
//                            'name' =>
//                                array (
//                                    'full_name' => 'John Doe',
//                                ),
//                            'address' =>
//                                array (
//                                    'address_line_1' => '2211 N First Street',
//                                    'address_line_2' => 'Building 17',
//                                    'admin_area_2' => 'San Jose',
//                                    'admin_area_1' => 'CA',
//                                    'postal_code' => '95131',
//                                    'country_code' => 'US',
//                                ),
//                        ),
                    'application_context' =>
                        array(
                            'brand_name' => 'Indigenous Lifestyle',
                            'locale' => 'en-US',
                            'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                            'user_action' => 'SUBSCRIBE_NOW',
                            'payment_method' =>
                                array(
                                    'payer_selected' => 'PAYPAL',
                                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                                ),
                            'return_url' => $successURL,
                            'cancel_url' => $cancelURL,
                        ),
                )
            )
        );
    }

    public static function getApprovalLink($subscription)
    {
        foreach ($subscription->links as $link) {
            if ($link->rel == 'approve') {
                return $link->href;
            }
        }
    }

    public static function updateProduct($productId, $description = '', $category = '', $image_url = '', $home_url = '')
    {
        if ($description != '') {
            $descriptionUpdate = Http::
            withHeaders(
                [
                    "Accept" => "application/json",
                    "Authorization" => self::getToken(),
                    "Content-Type" => "application/json",
                ])
                ->patch(env('PAYPAL_MODE') . "/v1/catalogs/products/$productId",
                    array(
                        0 =>
                            array(
                                'op' => 'replace',
                                'path' => '/description',
                                'value' => substr($description, 0, 127),
                            ),
                    )
                );
        }
        if ($category != '') {
            $categoryUpdate = Http::
            withHeaders(
                [
                    "Accept" => "application/json",
                    "Authorization" => self::getToken(),
                    "Content-Type" => "application/json",
                ])
                ->patch(env('PAYPAL_MODE') . "/v1/catalogs/products/$productId",
                    array(
                        0 =>
                            array(
                                'op' => 'replace',
                                'path' => '/category',
                                'value' => $category,
                            ),
                    )
                );
        }
        if ($image_url != '') {
            $image_urlUpdate = Http::
            withHeaders(
                [
                    "Accept" => "application/json",
                    "Authorization" => self::getToken(),
                    "Content-Type" => "application/json",
                ])
                ->patch(env('PAYPAL_MODE') . "/v1/catalogs/products/$productId",
                    array(
                        0 =>
                            array(
                                'op' => 'replace',
                                'path' => '/image_url',
                                'value' => $image_url,
                            ),
                    )
                );
        }
        if ($home_url != '') {
            $home_urlUpdate = Http::
            withHeaders(
                [
                    "Accept" => "application/json",
                    "Authorization" => self::getToken(),
                    "Content-Type" => "application/json",
                ])
                ->patch(env('PAYPAL_MODE') . "/v1/catalogs/products/$productId",
                    array(
                        0 =>
                            array(
                                'op' => 'replace',
                                'path' => '/image_url',
                                'value' => $home_url,
                            ),
                    )
                );
        }

    }

    public static function getAllProducts()
    {
        return json_decode(Http::withHeaders(['Content-Type' => 'application/json', 'Authorization' => self::getToken()])
            ->get(env('PAYPAL_MODE') . '/v1/catalogs/products'));
    }

    public static function getProductDetails()
    {
        return json_decode(Http::withHeaders(['Content-Type'=>'application/json', 'Authorization' => self::getToken()])
            ->get(env('PAYPAL_MODE').'/v1/catalogs/products/PROD-2DR504317S178273K'));
    }
}
