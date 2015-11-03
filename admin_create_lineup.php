<?php
session_start();
require_once 'mysqli_connect.php';
include 'account_utils.php';
include 'constants.php';
if (!isset($_SESSION['login']) or !is_admin($dbc)) {
	header("Location: index.php");
	exit;
}

if (isset($_GET["week"])) {
	$week = $_GET['week'];
} else {
	$week = WEEK;
}

if (isset($_POST['new_game'])) {
	create_game($dbc, $week, $_POST['home'], $_POST['away']);
}

$query = "SELECT t.id, name, rank from teams t left join rankings r on t.id=r.team_id and r.week=" . $week . " order by r.rank IS NULL, r.rank, t.name";
$games = "SELECT g.id as 'game_id', home_team_id, away_team_id, h_rank.rank as 'h_rank', h.name as 'home', a.name as 'away', a_rank.rank as 'a_rank' FROM games g INNER JOIN teams h ON g.home_team_id = h.id INNER JOIN teams a ON g.away_team_id=a.id LEFT JOIN rankings h_rank ON h_rank.team_id=h.id AND h_rank.week=g.week LEFT JOIN rankings a_rank ON a_rank.team_id=a.id AND a_rank.week=g.week WHERE g.week=" . $week . " ORDER BY g.id";

$response = @mysqli_query($dbc, $query);
$games_response = @mysqli_query($dbc, $games);

if ($response and $games_response) {

	while ($row = mysqli_fetch_array($response)) {

		$name = $row['name'];
		$id = $row['id'];
		$rank = $row['rank'];

		if (!is_null($rank)) {
			$name = '#' . $rank . ' ' . $name;
		}

		$teams[$id] = $name;
	}

	while ($row = mysqli_fetch_array($games_response)) {

		$home = $row['home'];
		$home_rank = $row['h_rank'];
		$away = $row['away'];
		$away_rank = $row['a_rank'];

		if (!is_null($home_rank)) {
			$home_rank = '#' . $home_rank . ' ';
		}

		if (!is_null($away_rank)) {
			$away_rank = '#' . $away_rank . ' ';
		}

		echo '<p>' . $home_rank . $home . ' @ ' . $away_rank . $away . '</p>';

	}

	echo '<form action="admin_create_lineup.php" value="new_game" method="post">';

	echo '<select name="home">';
	foreach ($teams as $id => $name) {
		echo '<option value="' . $id . '">' . $name . '</option>';
	}

	echo '</select> @ ';

	echo '<select name="away">';
	foreach ($teams as $id => $name) {
		echo '<option value="' . $id . '">' . $name . '</option>';
	}

	echo '<p><input type="submit" name="new_game" value="Send" /></p></form>';

} else {

	echo "Couldn't issue database query";

	echo mysqli_error($dbc);

}

mysqli_close($dbc);

?>