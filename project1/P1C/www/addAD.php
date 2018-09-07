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

<h1>Add Actor or Director</h1>
<form>
<input type = "radio" name = "person" value = "actor">Actor
<input type = "radio" name = "person" value = "director">Director
<br><br>
First Name
<br>
<input type = "text" name = "firstname">
<br><br>
Last Name
<br>
<input type = "text" name = "lastname">
<br><br>
<input type = "radio" name = "gender" value = "Male">Male
<input type = "radio" name = "gender" value = "Female">Female
<br><br>
Date of Birth
<br>
<input type = "text" name = "dob">
<br><br>
Date of Death
<br>
<input type = "text" name = "dod">
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
	$required = array('person','firstname', 'lastname', 'gender', 'dob');
	$error = false;
	foreach($required as $field) {
		if (empty($_GET[$field])) {
			$error = true;
		}
	}

	if ($error) {
		echo "Please fill out all required fields.";
	} else {
		$person = $_GET['person'];
		$first = $_GET['firstname'];
		$last= $_GET['lastname'];
		$gender = $_GET['gender'];
		$dob = $_GET['dob'];
		if (empty($_GET['dod']))
			$dod = "NULL";
		else {
			$dod = $_GET['dod'];
			$dod = "'$dod'";
		}
	
		$rs = $db->query("update MaxPersonID set id = id + 1;");
		$rs = $db->query("select id from MaxPersonID;");
		$row = $rs->fetch_row();
		$id = $row[0];


		if ($person == 'actor')
			$query = "insert into Actor values ($id,'$last','$first','$gender','$dob',$dod);";
		else 
			$query = "insert into Director values ($id,'$last','$first','$dob',$dod);";
		$db->query($query);
	}
}

$db->close();
?>

</body>
</html>
