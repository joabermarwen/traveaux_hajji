@foreach ($subscriptions as $subscription)
    <div class="col-xxl-4 col-lg-4 col-md-6">
        <div class="single-pricing single-pricing-border radius-10">
            <div class="single-pricing-top d-flex gap-3 flex-wrap align-items-center">
                <div class="single-pricing-brand">
                    <img src="{{ getImage(getFilePath('subscription') . '/' . $subscription->logo) }}" alt="logo">
                    {{-- {!! render_image_markup_by_attachment_id($subscription->logo ?? '') !!} --}}
                </div>
                <div class="single-pricing-top-contents">
                    <h5 class="single-pricing-title"> {{ $subscription->title ? trans_case($subscription->title) : '' }}
                    </h5>

                </div>
            </div>
            <ul class="single-pricing-list list-style-none">
                @foreach ($subscription->features as $feature)
                    @if ($feature->status == 'on')
                        <li class="single-pricing-list-item">
                            <span class="single-pricing-list-item-icon">
                                <i class="fa-solid fa-check"></i>
                            </span> {{ $feature->feature ? trans_case($feature->feature) : '' }}
                        </li>
                    @else
                        <li class="single-pricing-list-item">
                            <span class="single-pricing-list-item-icon cross-icon">
                                <i class="fa-solid fa-xmark"></i>
                            </span>{{ $feature->feature ? trans_case($feature->feature) : '' }}
                        </li>
                    @endif
                @endforeach
            </ul>
            <h3 class="single-pricing-price"> {{ $subscription->price }} €
                <sub>/{{ ucfirst(trans_case($subscription->subscription_type?->type)) }}</sub>
            </h3>
            <div class="btn-wrapper mt-4">
                <button class="cmn-btn btn-bg-gray btn-small w-100 choose_plan" data-bs-toggle="modal"
                    data-id="{{ $subscription->id }}" data-price="{{ $subscription->price }}"
                    @if (Auth::check()) data-bs-target="#paymentGatewayModal" @else data-bs-target="#loginModal" @endif>{{ __('Choose Plan') }}</button>
            </div>
        </div>
    </div>
@endforeach

@if (empty($type_id))
    {!! $subscriptions->links() !!}
@endif
