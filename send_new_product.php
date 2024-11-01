<?php
	header('Content-type: text/html; charset=UTF-8');
	$prod = ucfirst($_GET["prod"]);
	$fat = $_GET["fat"];
	$carbs = $_GET["carbs"];
	$protein = $_GET["protein"];
	$alcohol = $_GET["alcohol"];
	$fat = str_replace(",",".",$fat);
	$carbs = str_replace(",",".",$carbs);
	$protein = str_replace(",",".",$protein);
	$alcohol = str_replace(",",".",$alcohol);
	$amount = $_GET["amount"];
	$size_type = $_GET["size_type"];
	$category = $_GET["category"];
	
	$servername = "91.184.19.128"; // Change to your MySQL server's hostname or IP address
	$username = "p421706_voeding"; // Replace with your MySQL username
	$password = "d7uef7kX~pnkE"; // Replace with your MySQL password
	$database = "p421706_voeding"; // Replace with your database name
	
	// Create a connection to the MySQL database
	$conn = new mysqli($servername, $username, $password, $database);
	$conn->query("SET NAMES utf8");
	// Check if the connection was successful
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	

	// Close the connection when done (optional)
	
	$sql = "INSERT INTO product (name, category_id, size_type_id, size, fat, carbs, protein, alcohol) value (?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	

if ($stmt) {
	$stmt->bind_param("sddddddd", $prod, $category, $size_type, $amount, $fat, $carbs, $protein, $alcohol);

    // Execute the statement
    $stmt->execute();
}


$conn->close();
?>
