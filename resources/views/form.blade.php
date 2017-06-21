<!doctype html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<title>Laravel</title>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<form role="form" method="post">
					{!! csrf_field() !!}
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">User Information</h3>
						</div>
						<div class="box-body">
							<div class="form-group {{ $errors->first('first_name') ? 'has-error' : '' }}">
								<label class="control-label"></i>First Name</label>
								<input id="first_name" class="form-control" name="first_name" value="" type="text">
							</div>
							<div class="form-group {{ $errors->first('last_name') ? 'has-error' : '' }}">
								<label class="control-label"></i>Last Name</label>
								<input id="last_name" class="form-control" name="last_name" value="" type="text">
							</div>
							<div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
								<label class="control-label"></i>Email</label>
								<input id="email" class="form-control" name="email" value="" type="email">
							</div>
							<div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
								<label class="control-label"></i>Phone</label>
								<input id="phone" class="form-control" name="phone" value="" type="text">
							</div>
							<div class="box-footer">
								<button name="submit" type="submit" class="btn btn-primary">Create</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
