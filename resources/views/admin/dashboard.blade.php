@extends('layouts.base')

@section('content')
	<div class="row">
		<div class="page-header text-center">
			<h3>Admin Dashboard</h3>
			<p>The table below lists the raw entries from the db</p>
		</div>
	</div>
	<div class="row">
	<table class="table">
		<tr>
			<th>id</th>
			<th>first_name</th>
			<th>last_name</th>
			<th>email</th>
			<th>nonce</th>
			<th>verified</th>
			<th>created_at</th>
			<th>updated_at</th>
		</tr>
		@foreach ($subscribers as $subscriber)
		<tr>
			<td>{{ $subscriber->id }}</td>
			<td>{{ $subscriber->first_name }}</td>
			<td>{{ $subscriber->last_name }}</td>
			<td>{{ $subscriber->email }}</td>
			<td>{{ $subscriber->nonce }}</td>
			<td>{{ $subscriber->verified ? 'true' : 'false' }}</td>
			<td>{{ $subscriber->created_at }}</td>
			<td>{{ $subscriber->updated_at }}</td>
		</tr>
		@endforeach
	</table>

	</div>
@stop

