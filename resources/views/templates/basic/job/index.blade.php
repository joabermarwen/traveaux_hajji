@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="job-category padding-top padding-bottom ">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-end mb-3 mb-sm-4">
                <div class="search">
                    <select class="nice-select sortPrice">
                        <option value="" selected disabled>@lang('Sort By Rate')</option>
                        <option value="asc">@lang('Low to High')</option>
                        <option value="desc">@lang('High to Low')</option>
                    </select>
                </div>
                <div class="search ms-3">
                    <select class="nice-select dateSort">
                        <option value="">@lang('Filter By')</option>
                        <option value="today">@lang('Today')</option>
                        <option value="weekly">@lang('Weekly')</option>
                        <option value="monthly">@lang('Monthly')</option>
                    </select>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-3 col-md-12 sidebar-right theiaStickySidebar">
                    <div class="widget-box post-widget">
                        <h4 class="pro-title">@lang('Job Categories')</h4>
                        <ul class="latest-posts m-0">
                            <li>
                                <div class="post-thumb-category">
                                    <input type="checkbox" id="check" name="category_id" value="all" class="categoryFilter">
                                </div>
                                <div class="post-info-category">
                                    <label for="check">@lang('All Category')</label>
                                </div>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <div class="post-thumb-category">
                                        <input type="checkbox" id="check{{ $category->id }}" name="category_id" value="{{ $category->id }}" class="categoryFilter" @checked($category->id == request()->id)>
                                    </div>
                                    <div class="post-info-category">
                                        <label for="check{{ $category->id }}">{{ __($category->name) }}</label>
                                    </div>
                                </li>
                            @endforeach
                            <li>
                                <div class="post-info-category">
                                    <h6><a href="{{ route('category.list') }}" class="text--base">@lang('View all category')</a>
                                    </h6>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div class="announcement__details position-relative   border-0" id="jobs">
                        <div class="position-relative">
                            <div id="overlay">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>
                            </div>
                            <div class="overlay-2" id="overlay2"></div>
                            @include($activeTemplate . 'partials.jobs')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        (function($) {
            $("#overlay, #overlay2").css('display', 'none');

            $('.categoryFilter').on('change', function() {
                if ($('#check').is(':checked')) {
                    $("input[type='checkbox'][name='category_id']").not(this).prop('checked', false);
                }
                filterJob()
            });

            $('.sortPrice').on('change', function() {
                filterJob()
            });

            $('.dateSort').on('change', function() {
                filterJob()
            });

            function filterJob() {
                $("#overlay, #overlay2").fadeIn(300);
                let date = $('.dateSort').val();
                let sort = $('.sortPrice').val();
                let category_id = [];
                $.each($("input[name='category_id']:checked"), function() {
                    category_id.push($(this).val());
                });

                let url = "{{ route('job.sort') }}";
                $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        'date': date,
                        'sort': sort,
                        'category_id': category_id
                    },
                    success: function(response) {

                        $('#jobs').html(response);
                    }
                }).done(function() {
                    setTimeout(() => {
                        $("#overlay, #overlay2").fadeOut(300);
                    }, 500);
                });
            }


            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                let date = $('.dateSort').val();
                let sort = $('.sortPrice').val();

                fetchData(page, date, sort);
            });

            function fetchData(page, date, sort) {

                var date = date;
                var sort = sort;
                var category_id = [];
                $.each($("input[name='category_id']:checked"), function() {
                    category_id.push($(this).val());
                });

                $.ajax({
                    url: "{{ url('job/pagination/?page=') }}" + page,
                    data: {
                        'date': date,
                        'sort': sort,
                        'category_id': category_id
                    },
                    beforeSend:function(){
                        $(".preloader").show();
                    },
                    success: function(response) {
                        $('#jobs').html(response);
                        $(".preloader").hide();
                    }
                });
            }
        })(jQuery);
    </script>
@endpush
