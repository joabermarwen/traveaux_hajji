<?php

namespace App\Http\Controllers\Gateway\PaypalSdk;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Gateway\PaypalSdk\Core\PayPalHttpClient;
use App\Http\Controllers\Gateway\PaypalSdk\Core\ProductionEnvironment;
use App\Http\Controllers\Gateway\PaypalSdk\Core\SandboxEnvironment;
use App\Http\Controllers\Gateway\PaypalSdk\Orders\OrdersCaptureRequest;
use App\Http\Controllers\Gateway\PaypalSdk\Orders\OrdersCreateRequest;
use App\Http\Controllers\Gateway\PaypalSdk\PayPalHttp\HttpException;
use App\Models\Deposit;
use App\Models\Gateway;

class ProcessController extends Controller
{

    public static function process($deposit)
    {
        $pplaccount = Gateway::automatic()->where('alias', 'PaypalSdk')->firstOrFail();
        $paypalAcc = json_decode($pplaccount->gateway_parameters);

        $clientId = $paypalAcc->clientId->value;
        $clientSecret = $paypalAcc->clientSecret->value;
        $environment = new SandboxEnvironment($clientId, $clientSecret);  // You can change to ProductionEnvironment if needed
        $client = new PayPalHttpClient($environment);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $deposit->trx,
                "amount" => [
                    "value" => round($deposit->final_amount, 2),
                    "currency_code" => $deposit->method_currency
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('payment.cancel', ['deposit_id' => $deposit->id]),  // Set the cancel URL
                "return_url" => route('payment.success', ['deposit_id' => $deposit->id])   // Set the success URL
            ]
        ];

        try {
            // Execute PayPal request
            $response = $client->execute($request);

            // Store PayPal payment details (like the Stripe session ID)
            $deposit = Deposit::findOrFail($deposit->id);
            $deposit->btc_walet = $response->result->id; // PayPal transaction ID
            $deposit->save();

            // Prepare the redirect URL
            $send['redirect'] = true;
            $send['redirect_url'] = $response->result->links[1]->href; // PayPal redirect URL

        } catch (HttpException $ex) {
            // Handle error if PayPal request fails
            $send['error'] = true;
            $send['message'] = 'Failed to process with PayPal API';
        }

        return json_encode($send);
    }

    public function ipn()
    {
        $request = new OrdersCaptureRequest($_GET['token']);
        $request->prefer('return=representation');

        try {
            $deposit = Deposit::where('btc_walet',$_GET['token'])->where('status',Status::PAYMENT_INITIATE)->firstOrFail();
            $paypalAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
            $clientId = $paypalAcc->clientId;
            $clientSecret = $paypalAcc->clientSecret;
            $environment = new ProductionEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);

            $response = $client->execute($request);

            if(@$response->result->status == 'COMPLETED'){
                $deposit->detail = json_decode(json_encode($response->result->payer));
                $deposit->save();

                PaymentController::userDataUpdate($deposit);

                $notify[] = ['success', 'Payment captured successfully'];
                return redirect($deposit->success_url)->withNotify($notify);

            }else{

                $notify[] = ['error', 'Payment captured failed'];
                return redirect($deposit->failed_url)->withNotify($notify);
            }

        }catch (HttpException $ex) {
            return redirect($deposit->failed_url);
        }
    }

}
