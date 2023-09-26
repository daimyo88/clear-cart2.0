@extends('frontend.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
			<h3 class="page-title">{{ __('frontend/v4.shopping_cart') }}</h3>

            @if(!\App\Models\UserCart::isEmpty(\Auth::user()->id))
            <div class="card">
                <div class="card-header">{{ __('frontend/v4.shopping_cart') }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-transactions table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('frontend/v4.product') }}</th>
                                        <th scope="col">{{ __('frontend/v4.amount') }}</th>
                                        <th scope="col">{{ __('frontend/v4.price') }}</th>
                                        <th scope="col">{{ __('frontend/v4.total1') }}</th>
                                        <th scope="col">{{ __('frontend/v4.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $cartItem)
                                    <tr class="">
                                        <td>
                                            <a href="{{ route('product-page', $cartItem[0]->id) }}">
                                                @if($main_img = $cartItem[0]->getMainImage())
                                                    <img src="{{ '/files/' . $main_img->img_path }}" class="product-img-sm">
                                                @endif
                                                {{ htmlspecialchars($cartItem[0]->name) }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($cartItem[0]->asWeight())
                                                {{ $cartItem[1] }}{{ $cartItem[0]->getWeightChar() }}
                                            @elseif($cartItem[0]->asVariant())
                                                {{ \App\Models\UserCart::getVariant($cartItem[3]) -> title }}
                                            @else
                                                {{ $cartItem[1] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($cartItem[0]->asVariant())
                                                {{ \App\Models\Product::getFormattedPriceFromCent($cartItem[2]) }}
                                            @elseif($cartItem[0]->asTiered())
                                                {{ \App\Models\Product::getFormattedPriceFromCent($cartItem[0]->getTieredPriceFromAmount($cartItem[1] )) }}
                                            @else
                                                {{ $cartItem[0]->getFormattedPrice() }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ \App\Models\Product::getFormattedPriceFromCent($cartItem[2]) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('cart-item-delete', $cartItem[4]) }}">{{ __('frontend/v4.remove') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <hr />

                            <a href="{{ route('cart-clear') }}" class="btn btn-secondary">{{ __('frontend/v4.clearcart') }}</a>

                            <hr />
                            
                            <b>{{ __('frontend/v4.subtotal') }} </b>
                            {{ \App\Models\UserCart::getCartSubPrice(\Auth::user()->id, false) }} 
                            <br />
                            
                            <b>{{ __('frontend/v4.carttotal') }} </b>
                            {{ \App\Classes\Rabatt::priceformat(\App\Models\UserCart::getCartSubInCentCheckedCoupon(\Auth::user()->id, false)) }} <br />
							
                            <b>{{ __('frontend/v4.amount_to_pay') }} </b>
                            {{ \App\Classes\Rabatt::priceformat(\App\Models\UserCart::getCartSubInCentCheckedCoupon(\Auth::user()->id)) }} <br />
							
							@if(\App\Models\UserCart::hasDroplestProducts(\Auth::user()->id))
                            zzgl. Versandkosten
                            @endif
                            <hr />

                            <a href="{{ route('checkout') }}" class="btn btn-primary">{{ __('frontend/v4.continue_checkout') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">{{ __('frontend/v4.cart_is_empty') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
