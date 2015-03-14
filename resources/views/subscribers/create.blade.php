<html>
	<head>
		<title>Subscriptions</title>
	</head>
	<body>
		<h1>New Subscription</h1>

		{!! Form::open() !!}

			{!! Form::label('first_name', 'First Name:') !!}
			{!! Form::text('first_name') !!}

			{!! Form::label('last_name', 'Last Name:') !!}
			{!! Form::text('last_name') !!}

			{!! Form::label('email', 'Email:') !!}
			{!! Form::text('email') !!}

			{!! Form::submit('Submit') !!}

		{!! Form::close() !!}

		Connected to {{ $database }}
	</body>
</html>
