<?php
	$date = $_GET["date"];
	$distance = $_GET["distance"];
	$distance = str_replace(",",".",$distance);
	
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
	//check for existing record
	$sql = "SELECT * FROM settings";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
			if($row['name'] == 'gewicht'){
					$gewicht = $row['value'];
				}
	}	
	echo $gewicht;
	$sql = "SELECT * FROM hardlopen where date='$date'";
	$result = $conn->query($sql);
	$counter = $result->num_rows;
	if($counter == 0){
		
		$sql = "INSERT INTO hardlopen (date, distance, kcal) values (?, ?, ?)";
		$stmt = $conn->prepare($sql);

		if ($stmt) {
			$kcal = round($distance*$gewicht,2);
			$stmt->bind_param("sdd", $date, $distance, $kcal);

		// Execute the statement
			$stmt->execute();
		}

	}
	
	else{
		$sql = "UPDATE hardlopen SET distance = ?, kcal = ? where date = ?";
		$stmt = $conn->prepare($sql);

		if ($stmt) {
			$kcal = round($distance*$gewicht,2);
			$stmt->bind_param("dds", $distance, $kcal , $date);

		// Execute the statement
			$stmt->execute();
		}

	}
$conn->close();
?>
