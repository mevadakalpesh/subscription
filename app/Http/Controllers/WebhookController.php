<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Laravel\Cashier\Payment;
use Stripe\PaymentIntent;
use Stripe\Event;
use Stripe\Invoice;
use Laravel\Cashier\Subscription;
use App\Models\User;

class WebhookController extends CashierWebhookController
{
  /**
  * Handle a payment intent requires action event from Stripe.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Symfony\Component\HttpFoundation\Response
  */

  public function handleWebhook(Request $request) {
    $event = $request->type;
    $payload = $request->all();
    switch ($event) {
      case 'invoice.payment_failed':
        //$this->handleInvoicePaymentFailed($payload);
        break;

      case 'customer.subscription.deleted':
        // $this->handleCustomerSubscriptionDeleted($payload);
        break;

      case 'invoice.upcoming':
        //$this->handleInvoiceUpcoming($payload);
        break;

      case 'payment_intent.succeeded':
        $this->handlePaymentIntentSucceeded($payload);
        break;
      case 'customer.subscription.updated':
        $this->handleSubscriptionUpdated($payload);
        break;
      // Add more cases for other events as needed
      default:
        // Handle unrecognized event
        break;
    }

  }

  public function handleSubscriptionUpdated($payload) {
    $subscription = $payload['data']['object'];
    info('handleSubscriptionUpdated',[$payload]);
    // Update the subscription status in the database
    Subscription::where('stripe_id',$subscription['id'])->update([
      'stripe_status' => $subscription['status'],
    ]);
  }


  protected function handlePaymentIntentSucceeded($payload) {
    info('invoiceId', [$payload]);
    $paymentIntent = $payload['data']['object'];
    $invoiceId = $paymentIntent['invoice'];
    /* $invoice = Invoice::retrieve($invoiceId,[]);

    if ($invoice) {
      $subscriptionId = $invoice->subscription;
      $subscription = $this->getSubscription($subscriptionId);

      if ($subscription) {
        // Update the subscription status as desired
        $subscription->status = 'active'; // or any other desired status
        $subscription->save();
      }
    }
    */
  }
}