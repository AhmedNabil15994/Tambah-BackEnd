
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', '--') || {{ config('app.name') }}</title>
	<meta name="description" content="">
	<link rel="shortcut icon" href="{{url(config('setting.favicon'))}}" />
	<link rel="icon" href="{{url(config('setting.favicon'))}}" />
	<link rel="stylesheet" href="{{url('/poss/css/fontawesome.min.css')}}">
	<link rel="stylesheet" href="/poss/css/themify-icons.css">
	<link rel="stylesheet" href="/poss/css/linearicons.min.css">
	<link rel="stylesheet" href="/poss/css/bootstrap.min.css">
	<link rel="stylesheet" href="/poss/css/animate.min.css">
	<link rel="stylesheet" href="/poss/css/select2.min.css">
	<link rel="stylesheet" href="/poss/css/sweetalert.css">
	<link rel="stylesheet" href="/poss/css/toasty.css">
	<link rel="stylesheet" href="/poss/css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="/poss/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="/poss/css/responsive.dataTables.min.css">

	<link rel="stylesheet" href="/poss/css/style.css">
	<link rel="stylesheet" href="/poss/css/style_{{locale()}}.css">

	@yield('css')

</head>