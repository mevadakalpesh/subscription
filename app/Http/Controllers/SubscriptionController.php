<?php

namespace App\Http\Controllers;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\RepoInterface\StripeInterfaceRepo;
class SubscriptionController extends Controller
{
  public function __construct(
    public StripeInterfaceRepo $stripeInterfaceRepo
  ) {}
  public function user() {
    return auth()->user();
  }

  public function checkout(Request $request, $priceid) {
    //dd($this->user()->createSetupIntent());
    $price = $this->stripeInterfaceRepo->getPrice($priceid);
    $plan = $this->stripeInterfaceRepo->getProduct($price->product);
    $intent = $request->user()->createSetupIntent();
    $stripeKey = env('STRIPE_KEY');

    //dd($this->user()->createSetupIntent());
    return view('subscription.checkout', [
      'intent' => $intent,
      'plan' => $plan,
      'price' => $price,
      'stripeKey' => $stripeKey,
    ]);
  }

  public function subscriptionCreate(Request $request) {

    try {
      //$stripeCustomer = $request->user()->createOrGetStripeCustomer();
      //$request->user()->updateDefaultPaymentMethod($request->paymentMethod);
  
      
      $subscription = $request
      ->user()
      ->newSubscription('default', $request->price)
      ->create(
        $request->paymentMethod,
        [
          'name' => $request->name,
          'address' => [
            'line1' => $request->address,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
          ],
        ],
        [
          'off_session' => true,
          'metadata' => ['user_id' => $request->user()->id],
        ]);

    } catch (\Laravel\Cashier\Exceptions\IncompletePayment $e) {
  
    /*
      if ($e->payment->requiresPaymentMethod()) {
        dd('requiresPaymentMethod');
      } elseif ($e->payment->requiresConfirmation()) {
        dd('requiresConfirmation');
      } elseif ($e->payment->requiresAction()) {
        dd('requiresAction',$e->payment);
      }
      
      dd('stop',$e->payment);
      */
      return redirect()->route('cashier.payment', [
        $e->payment->id, 'redirect' => route('subscription.result')
      ]);


    }

    return view('subscription.success');
  }
}