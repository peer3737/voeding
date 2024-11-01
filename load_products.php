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
//$user_id = 6;
$endpoint = md5($_SERVER['REQUEST_URI']);
$cache_folder = 'cache';
$cache_file = $cache_folder.'/'.$endpoint.'.txt';// Cache duration (1 hour)
$cache_time = 3600;

// Function to save data to the cache file
function save_cache($file, $data) {
    file_put_contents($file, $data);
}

// Function to load data from cache if valid
function load_from_cache($file, $cache_time) {
    if (file_exists($file)) {
        // Check if cache is still valid
        if (time() - filemtime($file) < $cache_time) {
            return file_get_contents($file);
        }
    }
    return false; // Cache is invalid or doesn't exist
}
if (!is_dir($cache_folder)) {
    mkdir($cache_folder, 0755, true);
}

// Check if cached data exists and is still valid
$cached_data = load_from_cache($cache_file, $cache_time);

if ($cached_data !== false) {
    // Load data from cache
    echo $cached_data;
} else {

	$cat_id = $_GET["id"];
	if(isset($_GET["t"])){
		$meal = 1;
	}
	else{
		$meal = 0;
	}
	//$servername = "91.184.19.128"; // Change to your MySQL server's hostname or IP address
	//$username = "p421706_voeding"; // Replace with your MySQL username
	//$password = "d7uef7kX~pnkE"; // Replace with your MySQL password
	//$database = "p421706_voeding"; // Replace with your database name
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

	

	// Close the connection when done (optional)
	if($cat_id == "" && $meal == 0){
		$sql = "SELECT product.id as id, product.name as name, 0 as is_meal FROM product_content INNER JOIN product on product.id = product_content.product_id where product_content.category_id = 1 and user_id=$user_id
			UNION ALL
		SELECT meal.id as id, meal.name as name, 1 as is_meal from meal where user_id=$user_id
			order by is_meal, name ";
	}
	else if($cat_id == "" && $meal == 1){
		$sql = "SELECT product.id as id, product.name as name, product_content.is_meal FROM product_content INNER JOIN product on product.id = product_content.product_id where is_meal = 0 and user_id=$user_id order by product_content.is_meal, product.name";
	}
	else{
		$sql = "SELECT product.id as id, product.name as name, 0 as is_meal FROM product_content INNER JOIN product on product.id = product_content.product_id where product_content.category_id = 1 and user_id=$user_id
			UNION ALL
		SELECT meal.id as id, meal.name as name, 1 as is_meal from meal where user_id=$user_id
			order by is_meal, name";
	}
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

	echo $string;
$conn->close();
}
?>
