<?php
$endpoint = md5($_SERVER['REQUEST_URI']);
$cache_folder = 'cache';
$cache_file = $cache_folder.'/'.$endpoint.'.txt';// Cache duration (1 hour)
$cache_time = 360000;

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
	$id = $_GET["id"];
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
	
	$sql = "SELECT product.name as product_name, product.id as product_id, product_content.size as size, size_type.name as name FROM product inner join product_content on product_content.product_id = product.id inner join size_type on product_content.size_type_id = size_type.id where product.id = $id";
//	echo $sql;
	$result = $conn->query($sql);
	$string = "";
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$product_name = $row["product_name"];
			$product_id = $row["product_id"];
			$name = $row["name"];
			$size = $row["size"];
	
			

		}
	} 
	$output = $product_name.";".$size." ".$name.";".$product_id;
	
	        save_cache($cache_file, $output);
			echo $output;

$conn->close();
}
?>
