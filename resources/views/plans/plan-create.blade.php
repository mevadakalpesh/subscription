@extends('layouts.app')

@section('content')
<style>
  #card-element {
    width: 60% !important;
  }
</style>
<div class="container">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Add New Plan
  </h2>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <form action="{{route('store.plan')}}" method="post">
            @csrf
            <div class="form-group">
              <label for="plan name">Plan Name:</label>
              <input type="text" class="form-control" name="name" placeholder="Enter Plan Name">
              @error('name')
              <p class="text-danger">
                {{ $message }}
              </p>
              @enderror
            </div>

          
            <div class="form-group">
              <label for="cost">Plan Description:</label>
              <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
              @error('description')
              <p class="text-danger">
                {{ $message }}
              </p>
              @enderror
            </div>

            <div class="anothe-price-wapper mt-3 mb-3" data-totalrow="1">
              <div class="price-item row">
                <div class="form-group col-sm-6">
                  <label for="">Cost</label>
                </div>
                <div class="form-group col-sm-6">
                  <label for="">Time Period :</label>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-info" id="add_price">Add another price</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
<script>
  $(document).on('click', '#add_price', function() {
    var totalrow = $('.anothe-price-wapper').attr('data-totalrow');

    var price_item = '<div class="price-item row mt-3">'+
    '<div class="form-group col-sm-5">'+
    '<div class="input-group">'+
    '<div class="input-group-prepend">'+
    '<div class="input-group-text">$</div>'+
    '</div>'+
    '<input class="form-control" name="product_prices['+totalrow+'][unit_amount]" type="number" placeholder="Amount">'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-5">'+
    '<select name="product_prices['+totalrow+'][interval]" class="form-control">'+
    '<option value="month">Month</option>'+
    '<option value="year">Year</option>'+
    '<option value="week">Week</option>'+
    '<option value="day">Day</option>'+
    '</select>'+
    '</div>'+
    '<div class="form-group col-sm-5">'+
    '<input placeholder="How Much ?" class="form-control"name="product_prices['+totalrow+'][interval_count]" type="number">'+
    '</div>'+
    '<div class="col-sm-2">'+
    '<button type="button" class="btn btn-danger remove_price_fiels">Delete</button>'+
    '</div>'+
    '</div>';

    $('.anothe-price-wapper').append(price_item);
    $('.anothe-price-wapper').attr('data-totalrow', parseInt(totalrow) + 1);

  });

  $(document).on('click', '.anothe-price-wapper .remove_price_fiels', function(e) {
    e.preventDefault();
    $(this).parents('.price-item').remove();
  });
</script>
@endpush