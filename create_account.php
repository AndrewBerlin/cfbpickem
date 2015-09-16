<?php
session_start();
include 'account_utils.php';
require_once 'mysqli_connect.php';
if (isset($_SESSION['login'])) {
	header("Location: index.php");
	exit;
}?>
<br/>
<h1 class="text-center">Create Account</h1>
<br/>
<?php include 'header.php'; ?>
<?php
if (isset($_POST['create_account'])):
	$username = $_POST['username'];
	$password = $_POST['password'];

 	if(create_user($dbc, $username, $password, $_POST['password2'], $_POST['firstname'], $_POST['lastname'])):
		login($dbc, $username, $password);
		header("Location: picks_table.php");
	else: ?>
	Something went wrong and I\'m too tired to figure it out.

	<form action="create_account.php" method="post">
		<label>User name:</label>
		<input type="text" name="username" class="form-control"><br>
		User password:<br>
		<input type="password" name="password" class="form-control"><br>
		Re-enter password:<br>
		<input type="password" name="password2" class="form-control"><br>
		First Name:<br/>
		<input type="text" name="firstname" class="form-control"><br>
		Last Name:<br>
		<input type="text" name="lastname" class="form-control"><br>

		<p>
		<input type="submit" name="create_account" value="Create Account" />
		</p>
	</form>
	<?php endif; ?>

<?php else: ?>
<form action="create_account.php" method="post">
	<label>User name:</label>
	<input type="text" name="username" class="form-control">
	<br/>
	
	<label>User password:</label>
	<input type="password" name="password" class="form-control">
	<br/>
	
	<label>Re-enter password:</label>
	<input type="password" name="password2" class="form-control">
	<br/>
	
	<label>First Name:</label>
	<input type="text" name="firstname" class="form-control">
	<br/>
	
	<label>Last Name:</label>
	<input type="text" name="lastname" class="form-control">
	<br/>

	<p>
	<input type="submit" name="create_account" value="Create Account" class="btn btn-primary"/>
	</p>
</form>
<?php endif;
mysqli_close($dbc);
?>

<?php include 'footer.php'; ?>