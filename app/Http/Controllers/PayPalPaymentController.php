<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\PayPal as PayPalClient;



class PayPalPaymentController extends Controller
{

    public function handlePayment()
    {
        $product = [];
        $product['items'] = [
            [
                'name' => 'Nike Joyride 2',
                'price' => 112,
                'desc'  => 'Running shoes for Men',
                'qty' => 2
            ]
        ];
  
        $product['invoice_id'] = 1;
        $product['invoice_description'] = "Order #{$product['invoice_id']} Bill";
        $product['return_url'] = route('success.payment');
        $product['cancel_url'] = route('cancel.payment');
        $product['total'] = 224;
  
        $paypalModule = new PayPalClient;
        $provider = \PayPal::setProvider();
        $provider->getAccessToken();
        $provider->setCurrency('USD');
       
       // Create Recurring Daily Subscription
        $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
            ->addPlanTrialPricing('DAY', 7)
            ->addDailyPlan('Demo Plan', 'Demo Plan', 1.50)
            ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            ->setupSubscription('John Doe', 'sankar.webart@gmail.com', '2022-01-13');

        //Create Recurring Weekly Subscription
            // $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
            // ->addPlanTrialPricing('DAY', 7)
            // ->addWeeklyPlan('Demo Plan', 'Demo Plan', 30)
            // ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            // ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');


            // Create Recurring Monthly Subscription
            // $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
            //             ->addPlanTrialPricing('DAY', 7)
            //             ->addMonthlyPlan('Demo Plan', 'Demo Plan', 100)
            //             ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            //             ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');
        
            // $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
            // ->addPlanTrialPricing('DAY', 7)
            // ->addAnnualPlan('Demo Plan', 'Demo Plan', 600)
            // ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            // ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');

            // Create Recurring Subscription with Custom Intervals
            // $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
            // ->addCustomPlan('Demo Plan', 'Demo Plan', 150, 'MONTH', 3)
            // ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            // ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');
        

            // Create Subscription by Existing Product & Billing Plan
            // $response = $this->client->addProductById('PROD-XYAB12ABSB7868434')
            //     ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
            //     ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            //     ->setupSubscription('John Doe', 'john@example.com', $start_date);
            
        if($response['status'] == 'APPROVAL_PENDING'){
            return redirect($response['links'][0]['href']);
        }        
    }
   
    public function paymentCancel()
    {
        dd('Your payment has been declend. The payment cancelation page goes here!');
    }
  
    public function paymentSuccess(Request $request)
    {
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
  
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            dd('Payment was successfull. The payment success page goes here!');
        }
  
        dd('Error occured!');
    }
}