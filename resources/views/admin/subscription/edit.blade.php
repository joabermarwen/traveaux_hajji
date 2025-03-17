@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.subscription.update', $subscription->id) }}" class="disableSubmission" id="subscriptionForm"  method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <x-image-uploader name="logo" :imagePath="getImage(getFilePath('subscription') . '/' . @$subscription->logo, getFileSize('subscription'))" :size="false" class="w-100" id="profilePicUpload1" :required="false" />
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="single-input">
                                <label class="label-title">{{ trans_case('Subscription Type') }}</label>
                                <select name="type" id="type" class="form-control select2">
                                    <option value="">{{ trans_case('Select Type') }}</option>
                                    @foreach($all_types as $type)
                                        <option value="{{ $type->id }}" @selected($subscription->subscription_type_id == $type->id)>{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label> {{trans_case('Title')}}</label>
                                <input type="text" name="title" class="form-control" value="{{old('title',@$subscription->title)}}" required>
                            </div>
                            <div class="form-group">
                                <label> {{trans_case('price')}}</label>
                                <input type="number" name="price" step="0.01" class="form-control" value="{{old('price',@$subscription->price)}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="single-input col-md-6">
                        <div id="features">
                            @foreach($subscription->features as $feature)
                                <div class="attr single-input-feature-attr">
                                    <input name="feature[]" class="feature form-control" type="text" placeholder="{{ __('Enter feature') }}" value="{{ $feature->feature }}">
                                    <div class="checkbox-inline">
                                        <input name="status[]" type="checkbox" class="required-entry single-input-feature-checkbox check-input" {{ $feature->status == 'on' ? 'checked' : '' }}>
                                    </div>
                                    <button class="btn btn-danger btn-sm remove" type="button"><i class="fas fa-times"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button class="btn btn--primary add mt-3 mb-3 " type="button"><i class="fas fa-plus"></i>{{ __('Add Features') }}</button>
                    </div>

                    <button type="submit" class="btn btn--primary w-100 h-45 validate_subscription_type">
                        {{trans_case('Submit')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.subscription.index') }}" />
@endpush
@push('style')
    <style>
        .attr.single-input-feature-attr:not(:first-child) {
            margin-top: 15px;
        }
        .attr.single-input-feature-attr {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .single-input-feature-attr .checkbox-inline .check-input {
            height: 30px;
            width: 30px;
            margin-top: 0px;
            border-radius: 3px;
        }
        .single-input-feature-attr .checkbox-inline .check-input::after {
            font-size: 13px;
        }
    </style>
@endpush
@push('script')
    @include('admin.subscription.scripts')
@endpush
