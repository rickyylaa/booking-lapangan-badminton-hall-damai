@extends('backend.auth.layouts.app')
@section('title', 'Booking')

@section('content')
<section>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-10 text-center mx-auto">
				<img src="{{ asset('assets/images/element/error.svg') }}" alt="image" class="h-lg-500px mb-4">
				<h1 class="display-1 text-dark mb-0">404</h1>
				<h2>Oh no, something went wrong!</h2>
				<p class="mb-4">Either something went wrong or this page doesn't exist anymore.</p>
				<a href="{{ route('front.index') }}" class="btn btn-light mb-0">Take me to Homepage</a>
			</div>
		</div>
	</div>
</section>
@endsection
