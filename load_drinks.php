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

#echo $_SERVER['REQUEST_URI'];
#echo "<br>";
#echo md5($_SERVER['REQUEST_URI']);
#echo "<br>";
if ($cached_data !== false) {
    // Load data from cache
    echo $cached_data;
} else {
	$cat_id = $_GET["id"];
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
	if($cat_id == ""){
		$sql = "SELECT product.id as id, product.name as name FROM product_content inner join product on product.id = product_content.product_id where product_content.category_id = 2 and user_id=$user_id order by product.name";
	}
	else{
		$sql = "SELECT product.id as id, product.name as name FROM product_content inner join product on product.id = product_content.product_id where product_content.category_id = $cat_id and user_id=$user_id order by product.name";
	}
	//echo $sql;
	$result = $conn->query($sql);
	$string = "";
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$id = $row["id"];
			$name = $row["name"];
			$output = "$id;$name";
			$string .= $output.";";

		}
	} 
	$string = substr($string,0, strlen($string)-1);


       save_cache($cache_file, $string);

echo $string;
$conn->close();
}


?>
