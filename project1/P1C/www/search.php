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

<h1>Search for an Actor or Movie</h1>
<form>
	Search:
	<br>
	<input type = "text" name = "search">
	<br><br>
	<input type = "submit" name = "submit" value = "Submit">
</form>

<?php
$server = 'localhost';
$user = 'cs143';
$password = '';
$dbname = 'CS143';

$db = new mysqli($server, $user, $password, $dbname);

if ($db->connect_errno > 0) {
	die('Connection failed: ' . $db->connect_error);
}


if (isset($_GET['submit'])) {
	$search = explode(" ", $_GET['search']);

	// Matching Actors
	echo "<h2>Matching Actors are:</h2>";
	$query = "select id, first, last, dob from Actor where concat(first, ' ', last) like '%$search[0]%'";
	for ($i = 1; $i < count($search); $i++) {
		$query = $query . " and concat(first, ' ', last) like '%".$search[$i]."%'";
	}
	$query = $query . ";";
	$rs = $db->query($query);

	// Display results
	$header_flag = 1;
	echo "<table>";
	while ($row = $rs->fetch_assoc()) {
		if ($header_flag) {
			echo "<tr><td>Name and Date of Birth</td></tr>";
			$header_flag = 0;
		}
		echo "<tr><td>";
		echo "<a href=\"showA.php?id=".$row['id']."\">".$row['first']." ".$row['last']." (".$row['dob'].")</a><br>";
		echo "</td></tr>";
	}
	echo "</table>";

	// Matching Movies
	echo "<br><br>";
	echo "<h2>Matching Movies are:</h2>";
	$query = "select id, title, year from Movie where title like '%$search[0]%'";
	for ($i = 1; $i < count($search); $i++) {
		$query = $query . " and title like '%".$search[$i]."%'";
	}
	$query = $query . ";";

	// Display results
	$header_flag = 1;
	echo "<table>";
	$rs = $db->query($query);
	while ($row = $rs->fetch_assoc()) {
		if ($header_flag) {
			echo "<tr><td>Title and Year</td></tr>";
			$header_flag = 0;
		}
		echo "<tr><td>";
		echo "<a href=\"showM.php?id=".$row['id']."\">".$row['title']." ".$row['year']."</a><br>";
		echo "</td></tr>";
	}
	echo "</table>";
}

?>
</body>
</html>