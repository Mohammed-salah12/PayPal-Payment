<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalController extends Controller
{
    public function payment(){
        $data =[] ;
        $data['items']=[
            [
              'name' => 'its moh' ,
               'price' =>1000 ,
                'desc' => 'the best laravel developer',
                'qty' =>2
            ],
            [
                'name' => 'its leesun' ,
                'price' =>300 ,
                'desc' => 'the best react developer',
                'qty' =>4
            ]
        ];
        $data['invoice_id']=1;
        $data['invoice_description']="Order #{$data['invoice_id']} Invoice" ;
        $data['return_url']='http://127.0.0.1:8000/payment/success';
        $data['cancel_url']='http://127.0.0.1:8000/cancel';
        $data['total']=2600;

        $provider =new ExpressCheckout ;
        $response =  $provider->setExpressCheckout($data , true);
//        dd($response);
        return redirect($response['paypal_link']);

    }

    public function cancel(){
     return response()->json('payment canceled' , 402);

    }

    public function success(Request $request) {

        $provider= new ExpressCheckout ;
        $response = $provider->getExpressCheckoutDetails($request->token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            return response()->json('payment succeed', 200);
        }

        return response()->json('payment failed', 402);
    }

}
