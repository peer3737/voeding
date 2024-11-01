<?php
	$id = $_GET["id"];
	$servername = "91.184.19.128"; // Change to your MySQL server's hostname or IP address
	$username = "p421706_voeding"; // Replace with your MySQL username
	$password = "d7uef7kX~pnkE"; // Replace with your MySQL password
	$database = "p421706_voeding"; // Replace with your database name

	// Create a connection to the MySQL database
	$conn = new mysqli($servername, $username, $password, $database);

	// Check if the connection was successful
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	

	// Close the connection when done (optional)
	
	$sql = "SELECT size FROM product where id = $id";
	$result = $conn->query($sql);
	$string = "";
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$name = $row["size"];
	
			

		}
	} 
	
	echo $name;
$conn->close();
?>
