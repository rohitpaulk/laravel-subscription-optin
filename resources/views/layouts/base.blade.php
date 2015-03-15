<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Awesome Newsletter</title>
		<link rel="stylesheet" href="{{ asset('stylesheets/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('stylesheets/flat-ui.min.css') }}">
		<link rel="stylesheet" href="{{ asset('stylesheets/main.css') }}">
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				 <a class="navbar-brand" href="#">Awesome Newsletter</a>
			</div>
		</nav>

		<div class="container">
			@yield('content')
		</div>

		<script src="{{ asset('javascripts/vendor/jquery.min.js') }}"></script>
		<script src="{{ asset('javascripts/flat-ui.min.js') }}"></script>
		<script src="{{ asset('javascripts/validator.min.js') }}"></script>
	</body>
</html>
