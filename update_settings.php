<?php
	$value = $_GET["value"];
	$field = $_GET["field"];
	$value = str_replace(",",".",$value);
	
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

	$sql = "UPDATE settings set value = ? where name = ?";
	$stmt = $conn->prepare($sql);

if ($stmt) {
	$stmt->bind_param("ds", $value, $field);

    // Execute the statement
    $stmt->execute();
}


$conn->close();
?>
