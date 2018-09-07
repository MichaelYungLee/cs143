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
<h1>Add Movie Review</h1>
<form>
Name
<br>
<input type = "text" name = "name">
<br><br>
<select name = "movie">
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
	$query = "select title from Movie where id = $id;";
	$rs = $db->query($query);
	$row = $rs->fetch_assoc();
	echo "<option value = " . $id.">".$row['title']."</option>";
}
?>
</select>
<br><br>
<select name = "rating">
	<option value = 1>1</option>
	<option value = 2>2</option>
	<option value = 3>3</option>
	<option value = 4>4</option>
	<option value = 5>5</option>
</select>
<br><br>
Comments: 
<br>
<textarea rows="6" cols="60" name="comment">
</textarea>
<br><br>
<input type = "submit" name = "add" value = "Add Rating">

</form>

<?php 
if (isset($_GET['add'])) {
	$required = array('name','movie', 'rating');
	$error = false;
	foreach($required as $field) {
		if (empty($_GET[$field])) {
			$error = true;
		}
	}

	if ($error) {
		echo "Please fill out all required fields.";
	} else {
		$name = $_GET['name'];
		$movie = $_GET['movie'];
		$rating = $_GET['rating'];
		if (!empty($_GET['comment'])) {
			$comment = $_GET['comment'];
			$comment = "'$comment'";
		}
		else 
			$comment = "NULL";
	}

	$query = "insert into Review values('$name',NOW(),$movie,$rating,$comment);";
	$db->query($query);
	echo "<p>Successfully added the review! <a href=\"showM.php?id=".$movie."\">Click here to go back to the movie page</a></p>";
}

?>

</body>
</html>
