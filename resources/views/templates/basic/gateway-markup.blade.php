<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('subscription.buy') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="subscription_id" id="subscription_id">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4>{{ trans_case('Buy Subscription') }}</h4>

                </div>
                <div class="modal-body">
                    <div class="confirm-payment payment-border">
                        <div class="single-checkbox">
                            <div class="checkbox-inlines">
                                <label class="checkbox-label load_after_login" for="choose">
                                    {{-- @if (Auth::check() && Auth::user()->user_wallet?->balance > 0)
                                        {!! \App\Helper\PaymentGatewayList::renderWalletForm() !!}
                                        <span class="wallet-balance mt-2 d-block">{{ __('Wallet Balance:') }}
                                            <strong
                                                class="main-balance">{{ float_amount_with_currency_symbol(Auth::user()->user_wallet?->balance) }}</strong></span>
                                        <br>
                                        <span class="display_balance"></span>
                                        <br>
                                        <span class="deposit_link"></span>
                                    @endif --}}
                                    @php
                                        $gateways = App\Models\Gateway::automatic()->with('currencies')->where('status', 1)->get();

                                    @endphp
                                    <div class="payment-gateway-wrapper payment_getway_image">
                                        <input type="hidden" name="selected_payment_gateway" value="{{ $gateways[0]?->name }}">
                                        <ul>
                                            @foreach ($gateways as $gateway)
                                                @php
                                                     $class = ($gateways[0]?->name == $gateway->name) ? "selected active" : '';
                                                @endphp
                                                <li data-gateway="{{$gateway->name}}" @if($class!=='') class="{{$class}}" @endif>
                                                    <div class="img-select">
                                                        <img src="{{getImage(getFilePath('gateway'). '/' .$gateway->image) }}" style="width: 350px;" alt="{{$gateway->name}}">
                                                    </div>
                                                </li>

                                            @endforeach
                                        </ul>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger"
                        data-bs-dismiss="modal">{{ trans_case('Close') }}</button>
                    @if (Auth::guard('web')->check())
                        <button type="submit" class="btn-profile btn-bg-1 buy_subscription" id="confirm_buy_subscription_load_spinner">{{ trans_case('Buy Now') }} <span id="buy_subscription_load_spinner"></span></button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
