@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="container">
    <div class="alert alert-success">
        <h2>Payment Successful!</h2>
        <p>Your subscription has been activated successfully. Thank you for your purchase!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go to Dashboard</a>
    </div>
</div>
@endsection
