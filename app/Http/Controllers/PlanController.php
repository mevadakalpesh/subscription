<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlanCreateRequest;
use Dompdf\Exception;

class PlanController extends Controller
{
  protected $stripe;
  public function __construct() {
    $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
  }

  public function index() {
    $plans = $this->stripe->products->all(['active' => true]);
    return view('plans.plan-list', ['plans' => $plans]);
  }


  public function show($price_id, Request $request) {
    /*$the_product_price =  $this->stripe->prices->retrieve($price_id,[]);
        $the_product =  $this->stripe->products->retrieve($the_product_price['product'],[]);
        return view('subscribe', [
            'intent' => auth()->user()->createSetupIntent(),
            'the_product_price' => $the_product_price,
            'the_product' => $the_product,
        ]);*/
  }


  public function createPlan() {
    return view('plans.plan-create');
  }


  public function storePlan(PlanCreateRequest $request) {
    $data = $request->except('_token');

    //create stripe product
    $stripeProduct = $this->stripe->products->create([
      'name' => $data['name'],
      'description' => $data['description']
    ]);

    //create mutiple price
    if ($request->product_prices || !empty($request->product_prices)) {
      foreach ($request->product_prices as $product_price) {
        $this->stripe->prices->create(
          [
            'product' => $stripeProduct->id,
            'unit_amount' => $product_price['unit_amount'] * 100,
            'currency' => 'usd',
            'recurring' => [
              'interval' => $product_price['interval'],
              'interval_count' => $product_price['interval_count']
            ],
          ]
        );
      }
    }
    return redirect()->route('plans')->with('success', 'Plan Create Successfully');
  }



  public function deletePlan(Request $request) {

    try {
      $response = $this->stripe->products->update($request->product_id, ['active' => false]);
      setMessage('Plan Deleted Successfully.!');
      return redirect()->route('plans');
    }catch(\Exception $e) {
      setMessage($e->getError()->message, 'em');
      return redirect()->route('plans');
    }
  }
}