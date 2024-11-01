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

header('Content-type: text/html; charset=UTF-8');
$id_array = array();
$unit_array = array();
function save_cache($file, $data) {
    file_put_contents($file, $data);
}


foreach ($_GET as $key => $value) {
	if($key == "meal_name"){
		$meal_name = $value;
	}
	if(substr($key,0,10) == "product_id"){
		$id_array[] = $value;
	}
	if(substr($key,0,5) == "units"){
		$unit_array[] = $value;
	}
		
}

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
$conn->query("SET NAMES utf8");




$sql = "INSERT INTO meal (name, user_id) values (?, ?)";

	$stmt = $conn->prepare($sql);

if ($stmt) {
	$stmt->bind_param("sd", $meal_name, $user_id);

    // Execute the statement
    $stmt->execute();
}

$sql = "SELECT id from meal where user_id=$user_id order by id desc limit 1";

$stmt = $conn->prepare($sql);
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
$meal_id = intval($row["id"]);
}


for($i=0; $i<count($id_array); $i++){
	$product_id =  str_replace(",",".",$id_array[$i]);
	
	 
	$amount = str_replace(",",".",$unit_array[$i]);
	$sql = "INSERT INTO meal_product_link (meal_id, product_id, amount) values (?, ?, ?)";
	$stmt = $conn->prepare($sql);

if ($stmt) {
	$stmt->bind_param("idd", $meal_id, $product_id, $amount);

    // Execute the statement
    $stmt->execute();
}
	
}
		

	$endpoint = md5('/voeding/load_products.php?id=');
	$cache_folder = '../voeding/cache';
	$cache_file = $cache_folder.'/'.$endpoint.'.txt';// Cache duration (1 hour)
	$cache_time = 360000;
	$sql = "SELECT product.id as id, product.name as name, 0 as is_meal FROM product_content INNER JOIN product on product.id = product_content.product_id where product_content.category_id = 1 and product_content.user_id=$user_id 
			UNION ALL
		SELECT meal.id as id, meal.name as name, 1 as is_meal from meal where user_id=$user_id
			order by is_meal, name";



	
	$result = $conn->query($sql);
	$string = "";
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$id = $row["id"];
			$name = $row["name"];
			$is_meal = $row["is_meal"];
			
			$output = "$id;$name";
			if($is_meal == 1){
				$output = "$id;--$name--";
			}
			$string .= $output.";";

		}
	}

	
	$string = substr($string,0, strlen($string)-1);
	save_cache($cache_file, $string);



$conn->close();
?>
