<?php
session_start();
require_once 'mysqli_connect.php';
include 'account_utils.php';
if (!isset($_SESSION['login']) or !is_admin($dbc)) {
	header("Location: index.php");
	exit;
}

if (isset($_GET["week"])) {
	$week = $_GET['week'];
} else {
	$week = 9;
}

if (isset($_POST['new_rank'])) {
	save_rank($dbc, $_POST['team'], $_POST['rank'], $week);
}

$query = "SELECT id, name from teams order by name";
$teams_query = "SELECT t.id as 'id', t.name as 'name', r.rank as 'rank' from teams t inner join rankings r on t.id=r.team_id and r.week=" . $week . " order by r.rank";

$response = @mysqli_query($dbc, $query);
$teams_response = @mysqli_query($dbc, $teams_query);

if ($response and $teams_response) {

	while ($row = mysqli_fetch_array($response)) {

		$name = $row['name'];
		$id = $row['id'];

		$teams[$id] = $name;
	}

	while ($row = mysqli_fetch_array($teams_response)) {

		$name = $row['name'];
		$rank = $row['rank'];

		echo '<p>#' . $rank . ' ' . $name . '</p>';

	}

	echo '<form action="admin_create_rankings.php" value="new_rank" method="post">';

	echo '<select name="team">';
	foreach ($teams as $id => $name) {
		echo '<option value="' . $id . '">' . $name . '</option>';
	}

	echo '</select> : ';

	echo '<select name="rank">';
	echo '<option value="1">1</option>';
	echo '<option value="2">2</option>';
	echo '<option value="3">3</option>';
	echo '<option value="4">4</option>';
	echo '<option value="5">5</option>';
	echo '<option value="6">6</option>';
	echo '<option value="7">7</option>';
	echo '<option value="8">8</option>';
	echo '<option value="9">9</option>';
	echo '<option value="10">10</option>';
	echo '<option value="11">11</option>';
	echo '<option value="12">12</option>';
	echo '<option value="13">13</option>';
	echo '<option value="14">14</option>';
	echo '<option value="15">15</option>';
	echo '<option value="16">16</option>';
	echo '<option value="17">17</option>';
	echo '<option value="18">18</option>';
	echo '<option value="19">19</option>';
	echo '<option value="20">20</option>';
	echo '<option value="21">21</option>';
	echo '<option value="22">22</option>';
	echo '<option value="23">23</option>';
	echo '<option value="24">24</option>';
	echo '<option value="25">25</option></select';

	echo '<p><input type="submit" name="new_rank" value="Send" /></p></form>';

} else {

	echo "Couldn't issue database query";

	echo mysqli_error($dbc);

}

mysqli_close($dbc);

?>