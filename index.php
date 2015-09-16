<?php
session_start();
?>
<?php
include 'account_utils.php';
require_once 'mysqli_connect.php';?>


<?php include 'header.php'; ?>
	<div class="text-center">
		<br/>
		<img src="logo.png" style="width:200px;">
	</div>
<?php
if (isset($_POST['login'])):

	if (login($dbc, $_POST['username'], $_POST['password'])):
		header("Location: picks_table.php");
	else:?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">

	<h3 class="bg-danger" style="padding:10px;">Invalid username/password.</h3>

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
	<?php endif;

elseif (isset($_POST['create_account'])):
	header('Location: create_account.php');
elseif (isset($_POST['logout'])):
	logout();?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">

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
	<input type="submit" name="go_to_create_account" value="Create Account" class="btn btn-default"/>
	</p>
	</form>
<?php 
elseif (isset($_SESSION['login'])):
	header('Location: picks_table.php');
else:?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">

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
	<input type="submit" name="go_to_create_account" value="Create Account" class="btn btn-default"/>
	</p>
	</form>
<?php 
endif;
mysqli_close($dbc);
?>
 </div>
 </div><!--.row-->
<?php include 'footer.php'; ?>