<?php
session_start();
include 'constants.php';
include 'account_utils.php';
require_once 'mysqli_connect.php';
if (!isset($_SESSION['login'])) {
	header("Location: index.php");
	exit;
}
if (isset($_POST['logout'])) {
	logout();
	header("Location: index.php");
} elseif (!$_POST['submit']) {
	header("Location: index.php");
<<<<<<< Updated upstream
} else {
return;
	$query = "select id FROM games where week=9 ORDER BY id";
=======
} else if (true) {

	$query = "select id FROM games where week=3 ORDER BY id";
>>>>>>> Stashed changes
	$response = mysqli_query($dbc, $query);
	foreach ($_POST as $value) {
		if ($value == 'Send') {
			continue;
		}

		$row = mysqli_fetch_array($response);
		make_pick($dbc, $row['id'], $value, get_user_id($dbc));
	}
	header("Location: picks_table.php");
}

mysqli_close($dbc);

?>
