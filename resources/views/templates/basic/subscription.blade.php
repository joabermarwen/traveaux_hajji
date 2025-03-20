@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <main>

        <!-- Pricing area start -->
        <section class="pricing-area section-bg-2 pat-100 pab-100">
            <div class="container">
                {{-- <div class="section-title center-text">
                    <h2 class="title">{{ __('Subscription Plan') }}</h2>
                </div> --}}
                <div class="row mt-5">
                    <div class="pricing-tabs subsription-tabs">

                        <div class="tab-parents pricing-tabs-switch justify-content-center">
                            <span data-type_id="all" class="get_subscription_type_id subsription-btn active">{{__('All')}} </span>
                            @foreach ($subscription_types as $type)
                                <span data-type_id="{{ $type->id }}" class="get_subscription_type_id subsription-btn">
                                    {{ trans_case($type->type) }} </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row gy-4 mt-4 search_subscription_result">
                    @include('templates.basic.subscription-box')
                </div>
            </div>
        </section>
        <!-- Pricing area end -->
    </main>
    @include('templates.basic.login-markup')
    @include('templates.basic.gateway-markup')
@endsection
@push('script')
    @include('templates.basic.gateway-js')
    @include('templates.basic.subscription-js')
@endpush
