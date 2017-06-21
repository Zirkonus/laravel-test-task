<!doctype html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Zoho</title>
		<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<!-- Styles -->

	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="flex-center position-ref full-height">
					<div><a href="/add" class="btn btn-success">New User</a></div>
					<div>
						<h4> Our contact in DB</h4>
					</div>
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>First Name</th>
									<th>Second Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Buttons</th>
								</tr>
							</thead>
							<tbody>
								@foreach($contacts as $c)
									<tr>
										<td>{{$c->id}}</td>
										<td>{{$c->first_name}}</td>
										<td>{{$c->last_name}}</td>
										<td>{{$c->email}}</td>
										<td>{{$c->phone}}</td>
										<td><a href="/add/{{$c->id}}" class="btn btn-primary">Send to Zoho</a></td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
					@if($connect_users)
						<div>
							<h4> Our contact on Zoho</h4>
						</div>
						<div>
							<table class="table">
								<thead>
									<tr>
										<th>First Name</th>
										<th>Second Name</th>
										<th>Email</th>
										<th>Phone</th>
									</tr>
								</thead>
								<tbody>
									@foreach($connect_users as $c)
										<tr>
											<td>{{$c['First Name']}}</td>
											<td>{{$c['Last Name']}}</td>
											<td>{{$c['Email']}}</td>
											<td>{{$c['Phone']}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@endif
				</div>
			</div>
		</div>
	</body>
</html>
