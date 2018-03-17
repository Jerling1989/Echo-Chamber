<?php

	// CONNECTTION VARIABLE
	$connection = mysqli_connect('localhost', 'root', 'root', 'echo_chamber_db');

	// CONNECTION ERROR
	if(mysqli_connect_errno()) {
		echo 'Failed to connect: ' . mysqli_connect_errno();
	}

	$query = mysqli_query($connection, 'INSERT INTO test VALUES("2", "Johnny")');

?>


<!DOCTYPE html>
<html>
<head>
	<title>Echo Chamber</title>
</head>
<body>
	Welcome Jacob!
</body>
</html>