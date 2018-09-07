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
<h2>Movie Information:</h2>

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
	$query = "select title, year, rating, company from Movie where id = $id;";
	$rs = $db->query($query);

	while ($row = $rs->fetch_assoc()) {
		echo "<p>Movie Title: ".$row['title']."</p>";
		echo "<p>Year: ".$row['year']."</p>";
		echo "<p>MPAA Rating: ".$row['rating']."</p>";
		echo "<p>Production Company: ".$row['company']."</p>";
		$query = "select did from MovieDirector where mid = $id;";
		$md_rs = $db->query($query);
		$md_row = $md_rs->fetch_assoc();
		$did = $md_row['did'];
		$d_query = "select first, last from Director where id = $did;";
		$d_rs = $db->query($d_query);
		if ($d_rs->num_rows > 0) {
			$d_row = $d_rs->fetch_assoc();
			echo "<p>Director: ".$d_row['first']." ".$d_row['last']."</p>"; 
		}
		else 
			echo "<p>Director: </p>";
		$query = "select genre from MovieGenre where mid = $id;";
		$g_rs = $db->query($query);
		echo "<p>Genres: ";
		while ($g_row = $g_rs->fetch_row()) {
			echo $g_row[0]." ";
		}
		echo "</p>";
	}
}
?>

<h2>Actors</h2>
<?php
	$query = "select aid, role from MovieActor where mid = $id;";
	$rs = $db->query($query);

	$header_flag = 1;
	echo "<table>";
	while ($row = $rs->fetch_assoc()) {
		$aid = $row['aid'];
		$query = "select first, last from Actor where id = $aid;";
		$a_rs = $db->query($query);
		$a_row = $a_rs->fetch_assoc();
		if ($header_flag) {
			echo "<tr><td>Actor</td><td>Role</td></tr>";
			$header_flag = 0;
		}
		echo "<tr>";
		echo "<td><a href=\"showA.php?id=".$aid."\">".$a_row['first']." ".$a_row['last']."</a><br>";
		echo "<td> \"" . $row['role'] . "\"</td>";
		echo "</tr>";
	}
	echo "</table>";
?>
<h2>User Reviews</h2>
<?php 
	$query = "select avg(rating), count(rating) from Review where mid = $id";
	$rs = $db->query($query);
	$row = $rs->fetch_row();
	$avg_rating = $row[0];
	$rating_count = $row[1];
	echo "<p>The average rating for this movie is ".$avg_rating."/5 by ".$rating_count." users.</p>";
	echo "<p><a href=\"addReview.php?id=".$id."\">Leave a review here</a></p>";
	echo "<h4>User Comments:</h4>";
	$query = "select * from Review where mid = $id";
	$rs = $db->query($query);
	while ($row = $rs->fetch_assoc()) {
		echo $row['name']." rated this movie a ".$row['rating']." at ".$row['time']."<br>";
		if ($row['comment'] == "NULL") {
			echo "Comment: ";
		}
		else 
			echo "Comment: ".$row['comment'];
		echo "<br><br>";
	}
?>


</body>
</html>