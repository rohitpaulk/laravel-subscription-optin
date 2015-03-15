@extends('layouts.base')

@section('content')
	<div class="row">
		<div class="page-header text-center">
			<h3>Email Verification</h3>
		</div>
	</div>
	<div class="row text-center">
		<p>{{ $success_message }}</p>
	</div>
@stop
