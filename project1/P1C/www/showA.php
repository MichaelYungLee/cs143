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
<h2>Actor Information:</h2>

<?php 
$server = 'localhost';
$user = 'cs143';
$password = '';
$dbname = 'CS143';

$db = new mysqli($server, $user, $password, $dbname);

if ($db->connect_errno > 0) {
	die('Connection failed: ' . $db->connect_error);
}

if (!empty($_GET['id'])) {
	$id = $_GET['id'];
	$query = "select first, last, sex, dob, dod from Actor where id = $id;";
	$rs = $db->query($query);

	$header_flag = 1;
	echo "<table>";
	while ($row = $rs->fetch_assoc()) {
		if ($header_flag) {
			echo "<tr><td>Name</td><td>Sex</td><td>Date of Birth</td><td>Date of Death</td></tr>";
		}
		echo "<tr>";
		echo "<td>".$row['first']." ".$row['last']. "</td>";
		echo "<td>".$row['sex']."</td>";
		echo "<td>".$row['dob']."</td>";
		echo "<td>".$row['dod']."</td>";
		echo "</tr>";
	}
	echo "</table><br><br>";
}
?>

<h2>Actor's Roles and Movies</h2>
<?php
	$query = "select mid, role from MovieActor where aid = $id;";
	$rs = $db->query($query);

	$header_flag = 1;
	echo "<table>";
	while ($row = $rs->fetch_assoc()) {
		$mid = $row['mid'];
		$query = "select title from Movie where id = $mid;";
		$m_rs = $db->query($query);
		$m_row = $m_rs->fetch_assoc();
		if ($header_flag) {
			echo "<tr><td>Role</td><td>Movie Title</td></tr>";
			$header_flag = 0;
		}
		echo "<tr>";
		echo "<td> \"" . $row['role'] . "\"</td>";
		echo "<td><a href=\"showM.php?id=".$mid."\">".$m_row['title']."</a><br>";
		echo "</tr>";
	}
	echo "</table>";
?>


</body>
</html>