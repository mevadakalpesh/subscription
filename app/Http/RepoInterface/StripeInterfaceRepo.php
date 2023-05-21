<?php
namespace App\Http\RepoInterface;
interface StripeInterfaceRepo{
  public function getProduct($product_id, array $data=[]);
  public function getPrices(array $where=[]);
  public function getPrice($priceId, array $data = []);
  public function getPaymentIntent($paymentIntendId, array $data = []);

}
