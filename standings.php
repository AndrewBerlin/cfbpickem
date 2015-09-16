<?php
session_start();

require_once 'mysqli_connect.php';
include 'account_utils.php';
if (isset($_POST["logout"])) {
	logout();
	header("Location: index.php");
	exit;
}
if (!isset($_SESSION['login'])) {
	header("Location: index.php");
	exit;
}

include 'header.php';?>

<h1 class="text-center">Standings</h1>
<br/>

<?php
$games_query = "SELECT home.isACC as home_acc, away.isACC as away_acc, home_rank.rank as h_rank, away_rank.rank as a_rank FROM user_selections
	INNER JOIN games g ON g.id=game_id
	INNER JOIN teams home ON home.id=home_team_id
    INNER JOIN teams away ON away.id=away_team_id
    LEFT JOIN rankings home_rank ON home_rank.team_id=home_team_id AND g.week=home_rank.week
    LEFT JOIN rankings away_rank ON away_rank.team_id=away_team_id AND g.week=away_rank.week
    WHERE g.winner_team_id=selected_team_id AND user_id=";
// $weeks_query = ""
$names_query = "SELECT firstname, id FROM users";

// $weeks = mysqli_query($dbc, $weeks_query);
$names = mysqli_query($dbc, $names_query);

echo '<table class="table table-bordered" align="left"
cellspacing="5" cellpadding="8">';

// while ($row = mysqli_fetch_array($weeks)) {

$names = mysqli_query($dbc, $names_query);
$user_id = get_user_id($dbc);

$scores_array = array();

while ($name = mysqli_fetch_array($names)) {

	$response = mysqli_query($dbc, $games_query . $name["id"]);
	$result = 0;
	while ($game = mysqli_fetch_array($response)) {
		$home_acc = $game['home_acc'];
		$away_acc = $game['away_acc'];
		$home_rank = $game['h_rank'];
		$away_rank = $game['a_rank'];

		$points = $home_acc + $away_acc + ($home_rank !== null) + ($away_rank !== null);

		$result += $points;
	}
	if ($name['firstname'] == 'Kenny?') {
		$result += 3;
	}
	$scores_array[$name[0]] = $result;

}

arsort($scores_array);

echo '<tr>';
foreach ($scores_array as $name => $score) {
	echo '<td align="center"><b>' . $name . '</b></td>';
}
echo '</tr>';

foreach ($scores_array as $name => $score) {

	echo '<td align="center">' . $score . '</td>';

}

echo '</tr>';

// }

echo '</table>';

echo '
<form action="games.php"  method="post">
    <input type="submit" name="make_picks" value="Make Picks" class="btn btn-primary"/>
</form>
<br/>
<form action="picks_table.php" method="post">
    <input type="submit" name="logout" value="Log Out" class="btn btn-default"/>
</form>';

mysqli_close($dbc);

include 'footer.php';

?>

