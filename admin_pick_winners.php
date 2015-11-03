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
	$week = WEEK - 1;
}

if (isset($_POST['winners'])) {

	$query = "select id FROM games where week=" . $week . " ORDER BY id";
	$response = mysqli_query($dbc, $query);
	foreach ($_POST as $value) {
		if ($value == 'Send') {
			continue;
		}

		$row = mysqli_fetch_array($response);
		save_winner($dbc, $row['id'], $value);
	}
}
$query = "select g.winner_team_id as 'winner_team_id', g.id as 'game_id', home_team_id, away_team_id, h_rank.rank as 'h_rank', h.name as 'home', a.name as 'away', a_rank.rank as 'a_rank', h.isACC as 'home_acc', a.isAcc as 'away_acc' FROM games g INNER JOIN teams h ON g.home_team_id = h.id INNER JOIN teams a ON g.away_team_id=a.id LEFT JOIN rankings h_rank ON h_rank.team_id=h.id AND h_rank.week=g.week LEFT JOIN rankings a_rank ON a_rank.team_id=a.id AND a_rank.week=g.week WHERE g.week=" . $week . " ORDER BY g.id";

$response = @mysqli_query($dbc, $query);

if ($response) {

	echo '<table align="left"
	cellspacing="5" cellpadding="8">

	<td align="left"><b>Away</b></td>
	<td align="left"><b>Home</b></td>
	<td align="left"><b>Points</b></td></tr>
	';

	echo '<form action="admin_pick_winners.php" value="winners" method="post">
';

	while ($row = mysqli_fetch_array($response)) {

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

		$points = $home_acc + $away_acc + ($home_rank !== null) + ($away_rank !== null) + 2 * ($home_rank > 0 and $home_rank <= 5 and $away_rank > 0 and $away_rank <= 5) + ($home_rank > 0 and $home_rank <= 10 and $away_rank > 0 and $away_rank <= 10);
		$checked1 = "";
		$checked2 = "";
		if ($winner_team_id == $away_team_id) {
			$checked1 = "";
			$checked2 = "checked";
		} else if ($winner_team_id == $home_team_id) {
			$checked1 = "checked";
			$checked2 = "";
		}

		echo
		'
		<tr>
			<td align="left"><input type="radio" name="' . $game_id . '" value="' . $home_team_id . '" ' . $checked1 . '>' . $home . '</td>
		' .
		'	<td align="left"><input type="radio" name="' . $game_id . '" value="' . $away_team_id . '" ' . $checked2 . '>' . $away . '</td>
		' .
		'	<td align="left">' . $points . '</td>';
		echo '
		</tr>';

	}

	echo '</table><p>
		<input type="submit" name="winners" value="Send" />
		</p>
		<p>
		<input type="submit" name="logout" value="Logout" />
		</p>

	</form>';

} else {

	echo "Couldn't issue database query";

	echo mysqli_error($dbc);

}

mysqli_close($dbc);

?>