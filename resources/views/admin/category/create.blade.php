@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.category.store', @$category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <x-image-uploader name="image" :imagePath="getImage(getFilePath('category') . '/' . @$category->image, getFileSize('category'))" :size="false" class="w-100" id="profilePicUpload1" :required="false" />
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label> @lang('Name')</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name',@$category->name)}}" required>
                                </div>
                                <div class="form-group">
                                    <label> @lang('Description')</label>
                                    <textarea name="description" class="form-control" cols="30" rows="10" required>{{ old('description',@$category->description) }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn--primary w-100 h-45">
                                @lang('Submit')
                            </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.category.index') }}" />
@endpush

@push('style')
    <style>
        .profilePicUpload {
            margin-top: -20px;
        }
    </style>
@endpush

