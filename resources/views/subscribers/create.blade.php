@extends('layouts.base')

@section('content')
	<h1>New Subscription</h1>

	@if ($errors->any())
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	@endif


	{!! Form::open(['url' => 'subscribers']) !!}

		{!! Form::label('first_name', 'First Name:') !!}
		{!! Form::text('first_name') !!}

		{!! Form::label('last_name', 'Last Name:') !!}
		{!! Form::text('last_name') !!}

		{!! Form::label('email', 'Email:') !!}
		{!! Form::text('email') !!}

		{!! Form::submit('Submit') !!}

	{!! Form::close() !!}
@stop
