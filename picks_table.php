<?php
session_start();

require_once 'mysqli_connect.php';
include 'constants.php';
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

include 'header.php';

if (isset($_GET["week"])) {
	$week = $_GET['week'];
} else {
	$week = WEEK;
}
$picks_enabled = false;

$games_query = "select winner_team_id, g.id as 'game_id', home_team_id, away_team_id, h_rank.rank as 'h_rank', h.name as 'home', a.name as 'away', a_rank.rank as 'a_rank', h.isACC as 'home_acc', a.isAcc as 'away_acc' FROM games g INNER JOIN teams h ON g.home_team_id = h.id INNER JOIN teams a ON g.away_team_id=a.id LEFT JOIN rankings h_rank ON h_rank.team_id=h.id AND h_rank.week=g.week LEFT JOIN rankings a_rank ON a_rank.team_id=a.id AND a_rank.week=g.week WHERE g.week=" . $week . " ORDER BY g.id";
$names_query = "select firstname, id from users ORDER BY id";

$games = mysqli_query($dbc, $games_query);
$names = mysqli_query($dbc, $names_query);

echo '<table class="table table-bordered" align="left"
cellspacing="5" cellpadding="8"><tr><td></td>';

while ($row = mysqli_fetch_array($names)) {
	echo '<td align="center"><b>' . $row['firstname'] . '</b></td>';
}
echo '</tr>';

while ($row = mysqli_fetch_array($games)) {

	$game_id = $row['game_id'];
	$home = $row['home'];
	$away = $row['away'];
	$home_team_id = $row['home_team_id'];
	$away_team_id = $row['away_team_id'];
	$home_acc = $row['home_acc'];
	$away_acc = $row['away_acc'];
	$home_rank = $row['h_rank'];
	$away_rank = $row['a_rank'];
	$winner_team_id = $row['winner_team_id'];

	if ($home_rank) {
		$home = '#' . $home_rank . ' ' . $home;
	}

	if ($away_rank) {
		$away = '#' . $away_rank . ' ' . $away;
	}

	$points = $home_acc + $away_acc + ($home_rank !== null) + ($away_rank !== null);

	echo
	'
<tr><td align="left"><b><i>' . $home . ' at ' . $away . '</i></b></td>';

	$names = mysqli_query($dbc, $names_query);
	$user_id = get_user_id($dbc);
	while ($name = mysqli_fetch_array($names)) {
		$response = mysqli_query($dbc, "select name, user_id, selected_team_id from users u INNER JOIN user_selections pick ON pick.user_id=u.id and u.id=" . $name["id"] . " and pick.game_id=" . $game_id . " INNER JOIN teams t ON t.id=pick.selected_team_id");
		$result = mysqli_fetch_row($response);
		$pick = $result[0];
		$fmt_start = "";
		$fmt_end = "";

		if ($result[2] != $winner_team_id and !is_null($winner_team_id)) {
			$fmt = "bgcolor=red";
		} else {
			$fmt = "bgcolor=white";
		}

		if ($user_id != $result[1] and !is_null($pick) and $week == WEEK and $picks_enabled) {
			$pick = '-';
		}

		echo '<td ' . $fmt . ' align="center">' . $pick . '</td>';
	}

	echo '</tr>';

}

echo '</table>';

if ($picks_enabled) {
	echo '
   <form action="games.php"  method="post">
     <input type="submit" name="make_picks" value="Make Picks" class="btn btn-primary"/>
   </form><br />';
}
echo '<form action="standings.php" method="get">
    <input type="submit" value="Standings" class="btn btn-primary"/>
</form><br />
<form action="picks_table.php" method="get">
	Week:
    <input type="submit" name="week" value="1" class="btn btn-primary"/>
    <input type="submit" name="week" value="2" class="btn btn-primary"/>
    <input type="submit" name="week" value="3" class="btn btn-primary"/>
    <input type="submit" name="week" value="4" class="btn btn-primary"/>
    <input type="submit" name="week" value="5" class="btn btn-primary"/>
    <input type="submit" name="week" value="6" class="btn btn-primary"/>
    <input type="submit" name="week" value="7" class="btn btn-primary"/>
    <input type="submit" name="week" value="8" class="btn btn-primary"/>
    <input type="submit" name="week" value="9" class="btn btn-primary"/>
</form><br />
<form action="picks_table.php" method="post">
    <input type="submit" name="logout" value="Log Out" class="btn btn-primary"/>
</form>';

mysqli_close($dbc);

include 'footer.php';

?>
