@extends('layouts.app')

@section('content')
<style>
  .active_subscription {
    pointer-events: none;
  }

  .active_subscription .card {
    background: rgba(0,0,0,0.2);
  }
</style>
<div class="container">
  <div class="d-flex justify-content-between">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Plans
    </h2>
    @if(auth()->user()->is_admin)
    <a href="{{ route('create.plan') }}" class=" btn btn-info">Create New Plan</a>
    @endif
  </div>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          @if(session()->has('sm'))
          <div class="alert alert-success">
            {{ session()->get('sm') }}
          </div>
          @endif

          @if(session()->has('em'))
          <div class="alert alert-danger">
            {{ session()->get('em') }}
          </div>
          @endif

          <div class="row">
            @foreach($plans['data'] as $plan)

            <div class="col-sm-4 {{ auth()->user()->subscribed($plan->id) ? 'active_subscription' : '' }} ">
              <div class="card" style="width: 18rem;">
                <img src="..." class="card-img-top" alt="...">
                @if(auth()->user()->is_admin)
                <form class="delele-form" action="{{ route('delete.plan') }}" method="POST">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $plan->id }}">
                  <button type="submit" class="mr-3 btn-danger btn btn-sm">Delete</button>
                </form>
                @endif
                <div class="card-body">
                  <h5 class="card-title">{{  $plan['name']  }}</h5>
                  <p class="card-text">
                    {{ $plan['description'] }}
                  </p>
                  <?php $prices = getPlanPrices($plan->id); // echo '<pre>'; print_r($prices); ?>
                  @if ($prices || !empty($prices))
                  <ul>
                    @foreach ($prices as $price)
                    <li class="mt-3 ">
                      <div class="d-flex justify-content-between">
                        <h5>  ${{ number_format($price->unit_amount / 100,2) }} {{$price->recurring->interval }}</h5>
                        @if(!auth()->user()->is_admin)
                        <a href="{{ route('subscription.checkout',$price->id) }}" class=" btn btn-outline-dark pull-right">Choose</a>
                        @endif
                      </div>
                    </li>
                    @endforeach
                  </ul>
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('js')
<script>
  $(document).on('submit', 'form.delele-form', function() {
    return confirm('are you sure to delete this record?');
  });
</script>
@endpush
@endsection