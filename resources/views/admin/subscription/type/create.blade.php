@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.subscription.type.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-9">


                            <div class="form-group">
                                <label> {{trans_case('Type')}}</label>
                                <input type="text" name="type" class="form-control" value="{{old('type',@$type->type)}}" required>
                            </div>
                            <div class="form-group">
                                <label> {{trans_case('Validity')}}</label>
                                <input type="number" name="validity" :min="7" :max="365" class="form-control" value="{{old('validity',@$type->validity)}}" required>
                                <p class="text-info">{{trans_case('Validity must be a number between 7 to 365 days')}}</p>
                            </div>
                        </div>
                    </div>


                    <button type="submit" class="btn btn--primary w-100 h-45">
                        {{trans_case('Submit')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.subscription.type.index') }}" />
@endpush


