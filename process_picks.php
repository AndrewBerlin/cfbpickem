<?php
session_start();
	include('account_utils.php');
	require_once('mysqli_connect.php');
if (!isset($_SESSION['login'])) {
	header("Location: index.php");
	exit;
}
if (isset($_POST['logout'])) {
	logout();
	header("Location: index.php");
} elseif (!$_POST['submit']) {
	header("Location: index.php");
} else {

	$query = "select id FROM games where week=1 ORDER BY id";
	$response = mysqli_query($dbc, $query);
	foreach ($_POST as $value) {
		if ($value == 'Send') continue;

		$row = mysqli_fetch_array($response);
		make_pick($dbc, $row['id'], $value, get_user_id($dbc));
	}
		header("Location: picks_table.php");
}

mysqli_close($dbc);

?>