<!DOCTYPE html>
<html>
<head>
	<title>CFB Pickem</title>
</head>
<body>

<?php
	include('account_utils.php');
	require_once('mysqli_connect.php');
if (isset($_POST['create_account'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
		if (create_user($dbc, $username, $password, $_POST['password2'], $_POST['firstname'], $_POST['lastname'])) {
			login($dbc, $username, $password);
			session_start();
			$_SESSION['login'] = $_POST['username'];
			header ("Location: games.php");
		} else {
		echo 
'
Something went wrong and I\'m too tired to figure it out.

<form action="http://localhost/cfbpickem/php/create_account.php" method="post">
	User name:<br>
	<input type="text" name="username"><br>
	User password:<br>
	<input type="password" name="password"><br>
	Re-enter password:<br>
	<input type="password" name="password2"><br>
	First Name:<br>
	<input type="text" name="firstname"><br>
	Last Name:<br>
	<input type="text" name="lastname"><br>

	<p>
	<input type="submit" name="create_account" value="Create Account" />
	</p>
</form>
';
		}
	}
	 else {
		echo
'
<form action="http://localhost/cfbpickem/php/create_account.php" method="post">
	User name:<br>
	<input type="text" name="username"><br>
	User password:<br>
	<input type="password" name="password"><br>
	Re-enter password:<br>
	<input type="password" name="password2"><br>
	First Name:<br>
	<input type="text" name="firstname"><br>
	Last Name:<br>
	<input type="text" name="lastname"><br>

	<p>
	<input type="submit" name="create_account" value="Create Account" />
	</p>
</form>
';
	}
mysqli_close($dbc);
 ?>

</body>
</html>