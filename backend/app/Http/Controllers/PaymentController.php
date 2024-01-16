<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
// use Srmklive\PayPal\Services\ExpressCheckout;

class PaymentController extends Controller
{
            public function processPayment(Task $task)        {

            
            $provider = new ExpressCheckout;

            $data = [
                'items' => [
                    [
                        'name' => $task->name,
                        'price' => $task->price,
                        'qty' => 1,
                    ],
                ],
                'invoice_id' => uniqid(),
                'invoice_description' => "Payment for Task: {$task->name}",
                'return_url' => url("/tasks/{$task->id}/pay/success"),
                'cancel_url' => url("/tasks/{$task->id}/pay/cancel"),
            ];

            $response = $provider->setExpressCheckout($data);

            if ($response['ACK'] === 'Success') {
                return response()->json(['paypal_link' => $response['paypal_link']]);
            } else {
                return response()->json(['status' => 'failed']);
            }
        }




    public function paymentCancel(Task $task)
    {
        // Handle payment cancellation
        return response()->json(['status' => 'canceled']);
    }

    public function paymentSuccess(Task $task)
{
    // Verify payment status using PayPal API
    $provider = new ExpressCheckout;

    $response = $provider->getExpressCheckoutDetails(request('token'));

    if ($response['ACK'] === 'Success' && $response['PAYMENTSTATUS'] === 'Completed') {
        // Payment is successful, update task status
        $task->markAsPaid($response['TRANSACTIONID']);

        return response()->json(['status' => 'success']);
    } else {
        // Payment failed or not completed
        return response()->json(['status' => 'failed']);
    }
}
}
