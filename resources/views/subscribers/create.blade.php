@extends('layouts.base')

@section('content')
	<div class="row">
		<div class="page-header text-center">
			<h3>Subscribe</h3>
			<p>Enter your details to get started</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			{!! Form::open(['url' => 'subscribers', 'data-toggle' => 'validator']) !!}

				<div class="form-group">
					{!! Form::label('first_name', 'First Name:', ["class" => "control-label"]) !!}
					{!! Form::text('first_name', null, ["class" => "form-control", "placeholder" => "Sherlock", "required" => "true", "pattern" => "^[A-z]+$", "data-native-error" => "Only alphabetical characters, no spaces"]) !!}
					<div class="help-block with-errors">
						{{ $errors->first('first_name') }}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('last_name', 'Last Name:', ["class" => "control-label"]) !!}
					{!! Form::text('last_name', null, ["class" => "form-control", "placeholder" => "Holmes", "required" => "true", "pattern" => "^[A-z]+$", "data-native-error" => "Only alphabetical characters, no spaces"]) !!}
					<div class="help-block with-errors">
						{{ $errors->first('last_name') }}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('email', 'Email:', ["class" => "control-label"]) !!}
					{!! Form::text('email', null, ["class" => "form-control", "placeholder" => "sherlock@gmail.com", "required" => "true", "pattern" => "^(.+\@.+\..+)$", "data-native-error" => "Email address is not valid"]) !!}
					<div class="help-block with-errors">
						{{ $errors->first('email') }}
					</div>
				</div>

				<div class="form-group">
					{!! Form::submit('Submit', ["class" => "btn btn-primary btn-block"]) !!}
				</div>



			{!! Form::close() !!}
		</div>
	</div>
@stop
