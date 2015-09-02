<?php
require_once('mysqli_connect.php');
include('account_utils.php');

if (isset($_POST["logout"])) {
	logout();
	header("Location: index.php");
}

$games_query = "select g.id as 'game_id', home_team_id, away_team_id, h_rank.rank as 'h_rank', h.name as 'home', a.name as 'away', a_rank.rank as 'a_rank', h.isACC as 'home_acc', a.isAcc as 'away_acc' FROM games g INNER JOIN teams h ON g.home_team_id = h.id INNER JOIN teams a ON g.away_team_id=a.id LEFT JOIN rankings h_rank ON h_rank.team_id=h.id LEFT JOIN rankings a_rank ON a_rank.team_id=a.id ORDER BY g.id";
$names_query = "select firstname, id from users ORDER BY id";

$games = mysqli_query($dbc, $games_query);
$names = mysqli_query($dbc, $names_query);

	echo '<table border="1" align="left"
	cellspacing="5" cellpadding="8"><tr><td></td>';

	while ($row = mysqli_fetch_array($names)) {
	echo '<td align="left"><b>' . $row['firstname'] .'</b></td>';
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

		if ($home_rank) {
			$home = '#' . $home_rank . ' ' . $home;
		}

		if ($away_rank) {
			$away = '#' . $away_rank . ' ' . $away;
		}

		$points = $home_acc + $away_acc + ($home_rank !== null) + ($away_rank !== null);

		echo 
		'
		<tr><td align="left"><i>' . $home . ' at ' . $away . '</i></td>';

		$names = mysqli_query($dbc, $names_query);
		$user_id = get_user_id($dbc);
		while ($name = mysqli_fetch_array($names)) {
			$response = mysqli_query($dbc, "select name, user_id from users u INNER JOIN user_selections pick ON pick.user_id=u.id and u.id=" . $name["id"] . " and pick.game_id=". $game_id ." INNER JOIN teams t ON t.id=pick.selected_team_id");
			$result = mysqli_fetch_row($response);
			$pick = $result[0];
			if ($user_id != $result[1]) {
				$pick = '-';
			}

			echo '<td align="left">' . $pick .'</td>';
		}



		echo '</tr>';


	}

echo '</table>';

echo '
<form action="games.php">
    <input type="submit" name="make_picks" value="Make Picks"" />
</form>
<form action="index.php">
    <input type="submit" name="logout" value="Log Out"" />
</form>';

mysqli_close($dbc);

?>