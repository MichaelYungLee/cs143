<!DOCTYPE html>
<html>
<head>
<style>
ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
}
</style>
</head>
<body>
<ul>
	<li><a href="addAD.php">Add an Actor/Director</a></li>
	<li><a href="addM.php">Add a Movie</a></li>
	<li><a href="addMAR.php">Add Movie/Actor Relation</a></li>
	<li><a href="addMDR.php">Add Movie/Director Relation</a></li>
	<li><a href="search.php">Search</a></li>
</ul>

<h1>Add a Movie/Actor Relation</h1>
<form method = "get">
Movie Title:
<br>
<select name = "movie">
	<option disabled selected value> -- select an option -- </option>
<?php
$server = 'localhost';
$user = 'cs143';
$password = '';
$dbname = 'CS143';
$db = new mysqli($server, $user, $password, $dbname);
if ($db->connect_errno > 0) {
	die('Connection failed: ' . $db->connect_error);
}
$rs = $db->query("select id, title, year from Movie;");
while ($row = $rs->fetch_assoc()) {
	echo '<option value = ' . $row['id'] . '>' . $row['title'] . ' (' . $row['year'] . ')</option>';
}
?>
</select>
<br><br>
Actor:
<br>
<select name = "actor">
	<option disabled selected value> -- select an option -- </option>
<?php
$rs = $db->query("select id, first, last, dob from Actor;");
while ($row = $rs->fetch_assoc()) {
	echo '<option value = ' . $row['id'] . '>' . $row['first'] . ' ' . $row['last'] . ' (' . $row['dob'] . ')</option>';
}
?>
</select>
<br><br>
Role
<br>
<input type = "text" name = "role">
<br><br>
<input type = "submit" name = "add" value = "Add">
</form>
<?php

if (isset($_GET['add'])) {
	$required = array('movie', 'actor', 'role');
	$error = false;
	foreach($required as $field) {
		if (empty($_GET[$field]))
			$error = true;
	}
	if ($error) 
		echo "Please fill out all required fields.";
	else {
		$mid = $_GET['movie'];
		$aid = $_GET['actor'];
		$role = $_GET['role'];

		$query = "insert into MovieActor values($mid, $aid, '$role');";
		$db->query($query);
	}
}
$db->close();
?>

</body>
</html>
