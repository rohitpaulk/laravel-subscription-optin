@extends('layouts.base')

@section('content')
	<div class="row">
		<div class="page-header text-center">
			<h3>Subscribe Below</h3>
			<p>Enter your details to get started</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			@if ($errors->any())
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
				</ul>
			@endif


			{!! Form::open(['url' => 'subscribers']) !!}

				<div class="form-group">
					{!! Form::label('first_name', 'First Name:', ["class" => "control-label"]) !!}
					{!! Form::text('first_name', null, ["class" => "form-control", "placeholder" => "Sherlock"]) !!}
				</div>

				<div class="form-group">
					{!! Form::label('last_name', 'Last Name:', ["class" => "control-label"]) !!}
					{!! Form::text('last_name', null, ["class" => "form-control", "placeholder" => "Holmes"]) !!}
				</div>

				<div class="form-group">
					{!! Form::label('email', 'Email:', ["class" => "control-label"]) !!}
					{!! Form::text('email', null, ["class" => "form-control", "placeholder" => "sherlock@gmail.com"]) !!}
				</div>

				<div class="form-group">
					{!! Form::submit('Submit', ["class" => "btn btn-primary btn-block"]) !!}
				</div>



			{!! Form::close() !!}
		</div>
	</div>
@stop
