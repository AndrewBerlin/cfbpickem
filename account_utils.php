<?php


// $create_user = "INSERT INTO users (username, password, firstname, lastname) VALUES (?, ?, ?, ?)"

// $get_password = "SELECT password FROM users WHERE username=?"

define("GET_PICKS_1", "select name from users u INNER JOIN user_selections pick ON pick.user_id=u.id and u.id=");
define("GET_PICKS_2", " INNER JOIN teams t ON t.id=pick.selected_team_id");


function user_exists($dbc, $username)
{
	//count_users_with_username
	$query  = "SELECT count(*) FROM users WHERE username='" . $username . "'";
	
	// $affected_rows = mysqli_stmt_affected_rows($stmt);
	$response = mysqli_query($dbc, $query);
	$result = mysqli_fetch_row($response);

	return $result[0];
}

function create_user($dbc, $username, $password, $password2, $firstname, $lastname) {

	if ($password != $password2) {
		return false;
	}

	if (user_exists($dbc, $username)) {
		return false;
	}

	$create_user = "INSERT INTO users (username, password, firstname, lastname) VALUES (?, ?, ?, ?)";
	$stmt = mysqli_prepare($dbc, $create_user);
	$password_hash = password_hash($password, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "ssss", $username, $password_hash, $firstname, $lastname);
	mysqli_stmt_execute($stmt);

	$affected_rows = mysqli_stmt_affected_rows($stmt);

	return $affected_rows == 1;
}

function login($dbc, $username, $password) {
	//count_users_with_username
	$query  = "SELECT password FROM users WHERE username='" . $username . "'";
	
	// $affected_rows = mysqli_stmt_affected_rows($stmt);
	$response = mysqli_query($dbc, $query);
	$result = mysqli_fetch_row($response);

	if (password_verify($password, $result[0])) {
		$_SESSION['login'] = $username;
		return true;
	}

	return false;
}

function create_cookie($dbc, $username) {
	$create_token = "INSERT INTO tokens (username, token) VALUES (?, ?)";
	$stmt = mysqli_prepare($dbc, $create_token);
	$token = password_hash($username . "pickem", PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "ss", $username, $token);
	mysqli_stmt_execute($stmt);

	$affected_rows = mysqli_stmt_affected_rows($stmt);
	if ($affected_rows == 1) {
		setcookie("username", $username);
		setcookie("auth_token", $token);
	}

	mysqli_stmt_close($stmt);
}

function has_valid_token($dbc) {
	if (isset ($_COOKIE["username"]) & isset($_COOKIE["auth_token"])) {
		$query  = "SELECT token FROM tokens WHERE username='" . $_COOKIE["username"] . "'";
	
		$response = @mysqli_query($dbc, $query);
		if($response) {
			while ($row = mysqli_fetch_array($response)) {
				if ($row['token'] == $_COOKIE["auth_token"]) {
					return true;
				}
			}

			return false;
		}
	}
	return false;
}

function get_user_id($dbc) {
	$response = mysqli_query($dbc, "select id from users where username='" . $_SESSION['login'] . "'");
	$result = mysqli_fetch_row($response);


	return $result[0];
}

function logout() {
	session_destroy();
}

function make_pick($dbc, $game_id, $pick, $user_id) {
	$query = "SELECT * from user_selections where game_id=" . $game_id . " and user_id=" . $user_id;
	$response = mysqli_query($dbc, $query);
	if ($response->num_rows == 1) {
		$update_pick = "UPDATE user_selections SET selected_team_id=? WHERE id=?";
		$stmt = mysqli_prepare($dbc, $update_pick);
		$row = mysqli_fetch_row($response);
		$game_id = $row[0];

		mysqli_stmt_bind_param($stmt, "ii", $pick, $game_id);
		mysqli_stmt_execute($stmt);

	} else {
		$make_pick = "INSERT INTO user_selections (game_id, selected_team_id, user_id) VALUES (?, ?, ?)";
		$stmt = mysqli_prepare($dbc, $make_pick);

		mysqli_stmt_bind_param($stmt, "iii", $game_id, $pick, $user_id);
		mysqli_stmt_execute($stmt);
	}
}

function get_picks_for_user($dbc) {
	$response = mysqli_query($dbc, "select name from users u INNER JOIN user_selections pick ON pick.user_id=u.id and u.id=" . get_user_id($dbc) . " INNER JOIN teams t ON t.id=pick.selected_team_id");
	return $response;
}

function get_pick_id($dbc, $game_id, $user_id) {
	$response = mysqli_query($dbc, "select selected_team_id from user_selections where game_id=" . $game_id . " and user_id=" . $user_id);
	return @mysqli_fetch_row($response);
}

?>
