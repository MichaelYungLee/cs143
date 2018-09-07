<!DOCTYPE html>
<html>
<body>

<h1>Project 1 MovieDatabase Query Page</h1>
<p>Please type an SQL query and hit 'Submit' to query the MovieDatabase</p>

<form method="get">
<textarea rows="6" cols="60" name="query">
</textarea>
<input type="submit" value="Submit">
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
if (!empty($_GET['query'])) {
	$sql = $_GET['query'];
	$rs = $db->query($sql);
	$header_flag = 1;

	if ($rs->num_rows > 0) {
		echo "<table>";
		while ($row = $rs->fetch_assoc()) {
			if ($header_flag) {
				echo "<tr>";
				foreach($row as $col => $value) {
					echo "<td>" . $col . "</td>";
				}
				echo "</tr>";
				$header_flag = 0;
			}
			echo "<tr>";
			foreach($row as $col => $value) {
				echo "<td>" . $value . "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	else {
		echo "0 results";
	}
	$rs->free();
}
$db->close();

?>

</body>
</html>
