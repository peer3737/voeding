<?php
function save_cache($file, $data) {
    file_put_contents($file, $data);
}
	$prod_id = $_GET["prod_id"];
	$aantal = $_GET["amount"];
	$date = $_GET["date"];
	$aantal = str_replace(",",".",$aantal);
	$meal = intval($_GET["meal"]);
	
		$properties = parse_ini_file("db.properties"); 

		// Database connection details from the properties file
		$servername = $properties['servername'];
		$username = $properties['username'];
		$password = $properties['password'];
		$dbname = $properties['dbname'];
	// Create a connection to the MySQL database
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check if the connection was successful
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	
	if($meal == 0){
	// Close the connection when done (optional)
	
	$sql = "INSERT INTO content (product_id, amount, date) value (?, ?, ?)";
	$stmt = $conn->prepare($sql);

if ($stmt) {
	$stmt->bind_param("dss", $prod_id, $aantal, $date);

    // Execute the statement
    $stmt->execute();
}

	}

else{
	
	$meal_id = $prod_id;
	
	$sql = "SELECT product_id, amount from meal_product_link WHERE meal_id = ?";

	$stmt = $conn->prepare($sql);
	if ($stmt) {
	$stmt->bind_param("i", $meal_id);
	
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();


	while ($row = $result->fetch_assoc()) {
			$product_id = $row["product_id"];
			$product_amount = $row["amount"]*$aantal;
			$insert_sql = "INSERT INTO content (product_id, amount, date) value (?, ?, ?)";
			$insert_stmt = $conn->prepare($insert_sql);

		if ($insert_stmt) {
			$insert_stmt->bind_param("dss", $product_id, $product_amount, $date);

			// Execute the statement
			$insert_stmt->execute();
		}
			

		}
	}
}


$sql = "SELECT amount from content where product_id = $prod_id order by date desc LIMIT 10";
	$result = $conn->query($sql);
	$string = "";
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$content = $row['amount'];
			$string .= "$content".", "; 
	
			

		}
	} 
	$string = substr($string,0, strlen($string)-2);
		
	
	echo $string;
$conn->close();
$endpoint = md5("/voeding/history.php?id=$prod_id");
$cache_folder = 'cache';
$cache_file = $cache_folder.'/'.$endpoint.'.txt';// Cache duration (1 hour)
	   save_cache($cache_file, $string);

?>
