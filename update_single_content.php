<?php
// Start the session


session_start();

// Check if the user is logged in and the session is still valid
if(!isset($_SESSION['user_id']) || 
   (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7*24*60*60))) {
  // If not logged in or session expired, redirect to the login page
 
  header("Location: login.php");
  exit();
}

// Update the last activity timestamp
$_SESSION['last_activity'] = time(); 
$user_id = $_SESSION['user_id'];
?>
<?php

	$id = $_GET["id"];
	$content = $_GET["content"];
	$content = str_replace(",",".",$content);
	
$properties = parse_ini_file("db.properties"); 

	// Database connection details from the properties file
	$servername = $properties['servername'];
	$username = $properties['username'];
	$password = $properties['password'];
	$dbname = $properties['dbname'];
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check if the connection was successful
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	

	// Close the connection when done (optional)
	$sql = "SELECT size, product_id from product_content where product_id = (select product_id from content where id = ?) and user_id=$user_id";

	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);

    // Execute the statement
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$default_size = $row['size'];
			
		}		
	}
	$content = $content/$default_size;
	$sql = "UPDATE content set amount=? where id = ?";
	$stmt = $conn->prepare($sql);

if ($stmt) {
	$stmt->bind_param("di", $content, $id);

    // Execute the statement
    $stmt->execute();
}


$conn->close();
?>
