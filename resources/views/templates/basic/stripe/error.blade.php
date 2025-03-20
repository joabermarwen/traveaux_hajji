@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="container">
    <div class="alert alert-danger">
        <h2>Something Went Wrong</h2>
        <p>There was an issue processing your payment. Please try again later or contact support for assistance.</p>
        <a href="{{ route('/') }}" class="btn btn-primary">Return to Dashboard</a>
    </div>
</div>
@endsection
