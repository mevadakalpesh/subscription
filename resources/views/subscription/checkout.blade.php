@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          You will be charged Rs {{ number_format($price->unit_amount /
          100, 2) }} for {{ $plan->name }} Plan
        </div>

        <div class="card-body">

          <form action="{{ route('subscription.create') }}" method="post" id="payment-form" data-secret="{{ $intent->client_secret }}">
            @csrf
            <input type="hidden" name="plan" value="{{ $plan['id'] }}">
            <input type="hidden" name="price" value="{{ $price['id'] }}">
            <div class="">
              <div class="form-group">
                <label for="standard">{{ $plan->name }} -
                  ${{number_format($price->unit_amount / 100,2) }} / {{
                  $price->recurring->interval }}</label> <br>

              </div>
              <br>
              <h3>Billing Address</h3>
              <br>

              <div class="row">
                <div class="row">
                  <div class="col-xl-4 col-lg-4">
                    <div class="form-group">
                      <label for="">Name</label>
                      <input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="Name on the card">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="cardholder-name">Address</label>
                    <input type="text" name="address" id="cardholder-address" class="form-control">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="cardholder-name">Postal Code</label>
                    <input type="text" name="postal_code" id="cardholder-postal_code"
                    class="form-control">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="cardholder-name">City</label>
                    <input type="text" name="city" id="cardholder-city" class="form-control">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="cardholder-name">State</label>
                    <input type="text" name="state" id="cardholder-state" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="cardholder-name">Country</label>
                    <select id="cardholder-country" class="form-control" name="country">
                      <option value=" ">Select Country</option>
                      @foreach (getCountries() as $country_code => $country_name)
                      <option value="{{ $country_code }}" {{ "US"==$country_code ? 'selected' : '' }}>{{ $country_name }}</option>
                      @endforeach
                    </select>

                  </div>
                </div>


              </div>


              <label for="card-element">
                Credit or debit card
              </label>
              <div id="card-element">
                <!-- A Stripe Element will be inserted here. -->
              </div>

              <!-- Used to display form errors. -->
              <div id="card-errors" role="alert"></div>
            </div>
            <div class="d-flex">
              <button  type="submit" class="btn btn-primary" id="subscribe_now_btn" data-secret="{{ $intent->client_secret }}">Purchase</button>
              <img src="{{ asset('loading.gif') }}" class="mt-3 loading_image" width="70px" alt="" style="display: none;">
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="https://js.stripe.com/v3/"></script>
<script>
  // Create a Stripe client.
  var stripeKey = '{{ config("services.stripe.key") }}';
  
  var stripe = Stripe(stripeKey);
  // Create an instance of Elements.
  var elements = stripe.elements();


  // Custom styling can be passed to options when creating an Element.
  // (Note that this demo uses a wider set of styles than the guide below.)
  var style = {
    base: {
      color: '#32325d',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: 'antialiased',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4'
      }
    },
    invalid: {
      color: '#fa755a',
      iconColor: '#fa755a'
    }
  };

  // Create an instance of the card Element.
  var card = elements.create('card', {
    style: style
  });

  // Add an instance of the card Element into the `card-element` <div>.
  card.mount('#card-element');
  // Handle real-time validation errors from the card Element.
  card.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });

  // Handle form submission.
  var form = document.getElementById('payment-form');
  var cardHolderAddress = document.getElementById('cardholder-address');
  var cardHolderPostalCode = document.getElementById('cardholder-postal_code');
  var cardHolderCity = document.getElementById('cardholder-city');
  var cardHolderState = document.getElementById('cardholder-state');
  var cardHolderCountry = document.getElementById('cardholder-country');
  var clientSecret = form.dataset.secret;

  form.addEventListener('submit', async function(event) {
    $('#response_message').html(' ');
    event.preventDefault();
    $('#subscribe_now_btn').prop('disabled', true);
    $('.loading_image').show();

    if (cardHolderAddress.value && cardHolderPostalCode.value && cardHolderCity.value && cardHolderState.value && cardHolderCountry.value) {
      const {
        setupIntent,
        error
      } = await stripe.confirmCardSetup(
        clientSecret, {
          payment_method: {
            card,
            // billing_details: {
            //     address: {
            //         city: cardHolderCity.value,
            //         country: null,
            //         line1: cardHolderAddress.value,
            //         line2: null,
            //         postal_code: cardHolderPostalCode.value,
            //         state: cardHolderState.value
            //     },
            // }
          }
        }
      );

      if (error) {
        // Inform the user if there was an error.
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
      } else {
        // Send the token to your server.
        stripeTokenHandler(setupIntent);
      }
    } else {
      $('#response_message').html('<div class="alert alert-danger">All are Fields Required .Please Fill</div>');
    }
    // stripe.createToken(card).then(function(result) {
    //     if (result.error) {
    //     // Inform the user if there was an error.
    //     var errorElement = document.getElementById('card-errors');
    //     errorElement.textContent = result.error.message;
    //     } else {
    //     // Send the token to your server.
    //     stripeTokenHandler(result.token);
    //     }
    // });
  });

  // Submit the form with the token ID.
  function stripeTokenHandler(setupIntent) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type','hidden');
    hiddenInput.setAttribute('name','paymentMethod');
    hiddenInput.setAttribute('value',setupIntent.payment_method);
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
  }
</script>
@endpush