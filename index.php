<!DOCTYPE html>
<html>
<head>
	<title>CFB Pickem</title>
	<!-- Bootstrap -->
	<link href="assets/libs/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="text-center">
			<h1>College Football Pickem<br/><small>by Andrew Berlin</small></h1>
		</div>
		<div class="row">
			<div class="col-xs-6 col-xs-offset-3">

<?php
session_start();
	include('account_utils.php');
	require_once('mysqli_connect.php');
if (isset($_POST['login'])) {

		if (login($dbc, $_POST['username'], $_POST['password'])) {
			header ("Location: games.php");
		} else {
		echo 
'
Invalid username/password.

<form action="index.php" method="post">
	<label>User name:</label>
	<input type="text" name="username" class="form-control"/><br/>
	<label>Password:</label>
	<input type="password" name="password" class="form-control"/>

	<br/><br/>
	<input type="submit" name="login" value="Login" class="btn btn-primary"/>
	
</form>
<form action="create_account.php" method="post">
	<p><br/>
	<input type="submit" name="go_to_create_account" value="Create Account" class="btn btn-primary"/>
	</p>
</form>
';
		}

	} elseif (isset($_POST['create_account'])) {
			header('Location: create_account.php');
	} elseif (isset($_POST['logout'])) {
			logout();
			echo
'
<form action="index.php" method="post">
	<label>User name:</label>
	<input type="text" name="username" class="form-control"/><br/>
	<label>Password:</label>
	<input type="password" name="password" class="form-control"/><br/>

	<p>
	<input type="submit" name="login" value="Login" class="btn btn-primary"/>
	</p>
</form>
<form action="create_account.php" method="post">
	<p>
	<input type="submit" name="go_to_create_account" value="Create Account" class="btn btn-primary"/>
	</p>
</form>
';
	} elseif (isset($_SESSION['login'])) {
		header('Location: games.php');
	} else {
		echo
'
<form action="index.php" method="post">
	<label>User name:</label>
	<input type="text" name="username" class="form-control"/><br/>
	<label>Password:</label>
	<input type="password" name="password" class="form-control"/><br/>

	<p>
	<input type="submit" name="login" value="Login" class="btn btn-primary"/>
	</p>
</form>
<form action="create_account.php" method="post">
	<p>
	<input type="submit" name="go_to_create_account" value="Create Account" class="btn btn-primary"/>
	</p>
</form>
';
	}
mysqli_close($dbc);
 ?>
 			</div>
 		</div><!--.row-->
 	</div><!--.container-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>