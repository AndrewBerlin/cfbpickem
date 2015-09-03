<?php
session_start();

require_once('mysqli_connect.php');
include('account_utils.php');
if (isset($_POST["logout"])) {
logout();
header("Location: index.php");
exit;
}
if (!isset($_SESSION['login'])) {
header("Location: index.php");
exit;
}

echo '<!DOCTYPE html>
<html>
<head>
<title>CFB Pickem</title>
<!-- Bootstrap -->
<link href="assets/libs/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="text-center">
<h1>College Football Pickem<br/><small>by Andrew Berlin</small></h1>
</div>
<div class="row">
<div class="col-xs-6 col-xs-offset-3">';

$games_query = "select g.id as 'game_id', home_team_id, away_team_id, h_rank.rank as 'h_rank', h.name as 'home', a.name as 'away', a_rank.rank as 'a_rank', h.isACC as 'home_acc', a.isAcc as 'away_acc' FROM games g INNER JOIN teams h ON g.home_team_id = h.id INNER JOIN teams a ON g.away_team_id=a.id LEFT JOIN rankings h_rank ON h_rank.team_id=h.id LEFT JOIN rankings a_rank ON a_rank.team_id=a.id ORDER BY g.id";
$names_query = "select firstname, id from users ORDER BY id";

$games = mysqli_query($dbc, $games_query);
$names = mysqli_query($dbc, $names_query);

echo '<table class="table table-bordered" align="left"
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
<tr><td align="left"><b><i>' . $home . ' at ' . $away . '</i></b></td>';

$names = mysqli_query($dbc, $names_query);
$user_id = get_user_id($dbc);
while ($name = mysqli_fetch_array($names)) {
$response = mysqli_query($dbc, "select name, user_id from users u INNER JOIN user_selections pick ON pick.user_id=u.id and u.id=" . $name["id"] . " and pick.game_id=". $game_id ." INNER JOIN teams t ON t.id=pick.selected_team_id");
$result = mysqli_fetch_row($response);
$pick = $result[0];
if ($user_id != $result[1]) {
$pick = '-';
}

echo '<td align="center">' . $pick .'</td>';
}



echo '</tr>';


}

echo '</table>';

echo '
<form action="games.php"  method="post">
    <input type="submit" name="make_picks" value="Make Picks" />
</form>
<form action="picks_table.php" method="post">
    <input type="submit" name="logout" value="Log Out"/>
</form>';

mysqli_close($dbc);

?>
 </div>
 </div><!--.row-->
 </div><!--.container-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
