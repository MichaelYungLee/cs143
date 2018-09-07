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

<h1>Add Movie</h1>
<form>
Title:
<br>
<input type = "text" name = "title">
<br><br>
Company
<br>
<input type = "text" name = "company">
<br><br>
Year
<br>
<input type = "text" name = "year">
<br><br>
<select name = "rating">
	<option value = "G">G</option>
	<option value = "PG">PG</option>
	<option value = "PG-13">PG-13</option>
	<option value = "R">R</option>
	<option value = "NC-17">NC-17</option>
</select>
<br><br>
<input type = "checkbox" name = "genre[]" value = "Action">Action</input>
<input type = "checkbox" name = "genre[]" value = "Adult">Adult</input>
<input type = "checkbox" name = "genre[]" value = "Adventure">Adventure</input>
<input type = "checkbox" name = "genre[]" value = "Animation">Animation</input>
<input type = "checkbox" name = "genre[]" value = "Comedy">Comedy</input>
<input type = "checkbox" name = "genre[]" value = "Crime">Crime</input>
<input type = "checkbox" name = "genre[]" value = "Documentary">Documentary</input>
<input type = "checkbox" name = "genre[]" value = "Drama">Drama</input>
<input type = "checkbox" name = "genre[]" value = "Family">Family</input>
<input type = "checkbox" name = "genre[]" value = "Fantasy">Fantasy</input>
<input type = "checkbox" name = "genre[]" value = "Horror">Horror</input>
<input type = "checkbox" name = "genre[]" value = "Musical">Musical</input>
<input type = "checkbox" name = "genre[]" value = "Mystery">Mystery</input>
<input type = "checkbox" name = "genre[]" value = "Romance">Romance</input>
<input type = "checkbox" name = "genre[]" value = "Sci-Fi">Sci-Fi</input>
<input type = "checkbox" name = "genre[]" value = "Short">Short</input>
<input type = "checkbox" name = "genre[]" value = "Thriller">Trailer</input>
<input type = "checkbox" name = "genre[]" value = "War">War</input>
<input type = "checkbox" name = "genre[]" value = "Western">Western</input>
<br><br>
<input type = "submit" name = "add" value = "Add">

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
if (isset($_GET['add'])) {
	$required = array('title', 'company','year','rating');
	$error = false;
	foreach($required as $field) {
		if (empty($_GET[$field]))
			$error = true;
	}
	if ($error) 
		echo "Please fill out all required fields.";
	else {
		$title = $_GET['title'];
		$company = $_GET['company'];
		$year = $_GET['year'];
		$rating = $_GET['rating'];

		$rs = $db->query("update MaxMovieID set id = id + 1;");
		$rs = $db->query("select id from MaxMovieID;");
		$row = $rs->fetch_row();
		$id = $row[0];

		$query = "insert into Movie values ($id,'$title',$year,'$rating','$company');";
		$db->query($query);

		if (is_array($_GET['genre'])) {
			foreach($_GET['genre'] as $val) {
				$query = "insert into MovieGenre values ($id, '$val');";
				$db->query($query);
			}
		}
		else {
			$val = $_GET['genre'];
			$query = "insert into MovieGenre values ($id, '$val');";
			$db->query($query);
		} 
	}
}
$db->close();
?>
</body>
</html>
