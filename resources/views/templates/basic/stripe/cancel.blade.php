@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="container">
    <div class="alert alert-warning">
        <h2>Payment Canceled</h2>
        <p>It seems that your payment did not go through. Please try again or choose another payment method.</p>
        <a href="{{ route('/') }}" class="btn btn-primary">Back to Home</a>
    </div>
</div>
@endsection
