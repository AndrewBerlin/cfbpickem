<!DOCTYPE html>
<html>
<head>
	<title>CFB Pickem</title>
</head>
<body>

<?php
	include('account_utils.php');
	require_once('mysqli_connect.php');
if (isset($_POST['login'])) {
		if (login($dbc, $_POST['username'], $_POST['password'])) {
			session_start();
			$_SESSION['login'] = $_POST['username'];
			header ("Location: page1.php");
		} else {
		echo 
'
Invalid username/password.

<form action="index.php" method="post">
	User name:<br>
	<input type="text" name="username"><br>
	User password:<br>
	<input type="password" name="password">

	<p>
	<input type="submit" name="login" value="Login" />
	</p>
</form>
<form action="create_account.php" method="post">
	<p>
	<input type="submit" name="go_to_create_account" value="Create Account" />
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
	User name:<br>
	<input type="text" name="username"><br>
	User password:<br>
	<input type="password" name="password">

	<p>
	<input type="submit" name="login" value="Login" />
	</p>
</form>
<form action="create_account.php" method="post">
	<p>
	<input type="submit" name="go_to_create_account" value="Create Account" />
	</p>
</form>
';
	} elseif ($_SESSION['login']) {
		header('Location: games.php');
	} else {
		echo
'
<form action="index.php" method="post">
	User name:<br>
	<input type="text" name="username"><br>
	User password:<br>
	<input type="password" name="password">

	<p>
	<input type="submit" name="login" value="Login" />
	</p>
</form>
<form action="create_account.php" method="post">
	<p>
	<input type="submit" name="go_to_create_account" value="Create Account" />
	</p>
</form>
';
	}
mysqli_close($dbc);
 ?>

</body>
</html>