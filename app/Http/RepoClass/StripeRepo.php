<?php
namespace App\Http\RepoClass;
use App\Http\RepoInterface\StripeInterfaceRepo;
use Stripe\StripeClient;
class StripeRepo implements StripeInterfaceRepo {
  public $stripe;
  public function __construct() {
    $this->stripe = new StripeClient(config('services.stripe.secret'));
  }

  public function getProduct($product_id, array $data = []) {
    return $this->stripe->products->retrieve($product_id, $data);
  }

  public function getPrices(array $where = []) {
    return $this->stripe->prices->all($where);
  }

  public function getPrice($priceId, array $data = []) {
    return $this->stripe->prices->retrieve($priceId, $data);
  }
  
  public function getPaymentIntent($paymentIntendId, array $data = []){
    return $this->stripe->paymentIntents->retrieve($paymentIntendId,$data);
  }
}