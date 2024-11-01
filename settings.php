<?php
// Start the session


session_start();
session_destroy();

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
<head>
	<style>

		td{
			border:1px solid black;
			padding:6px;
		}
		table{
			border-collapse:collapse;
			max-width:100vw;
		}
		input.name{
			width:450px;
			font-size:10pt;
		}
		input.metric{
			width:50px;
			font-size:10pt;
		}
		input.content{
			width:50px;
			font-size:10pt;
		}

		</style>


<script>
function update_product(id, type){

	url = "update_product.php?id="+id+"&field="+type;
	var xhr = new XMLHttpRequest();
	
		// Configure the GET request
		xhr.open("GET", url, true);

		// Set up the onload event handler
		xhr.onload = function() {
			if (xhr.status === 200) {
			
				// Request was successful, process the response
	
			} else {
				// Request failed, handle the error
				console.error('Request failed with status:', xhr.status);
			}
		};

		// Set up the onerror event handler (for network errors)
		xhr.onerror = function() {
			console.error('Network error occurred');
		};

		// Send the GET request
		xhr.send();
}

function update_settings(type){
	value = document.getElementById(type).value;
	url = "update_settings.php?value="+value+"&field="+type;
	var xhr = new XMLHttpRequest();
	
		// Configure the GET request
		xhr.open("GET", url, true);

		// Set up the onload event handler
		xhr.onload = function() {
			if (xhr.status === 200) {
				console.log(xhr);
				// Request was successful, process the response
	
			} else {
				// Request failed, handle the error
				console.error('Request failed with status:', xhr.status);
			}
		};

		// Set up the onerror event handler (for network errors)
		xhr.onerror = function() {
			console.error('Network error occurred');
		};

		// Send the GET request
		xhr.send();
}

</script>
</head>
<?php
header('Content-type: text/html; charset=UTF-8');


		$properties = parse_ini_file("db.properties"); 

		// Database connection details from the properties file
		$servername = $properties['servername'];
		$username = $properties['username'];
		$password = $properties['password'];
		$dbname = $properties['dbname'];
	// Create a connection to the MySQL database
	$conn = new mysqli($servername, $username, $password, $dbname);
$conn->query("SET NAMES utf8");

// Check if the connection was successful
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM settings";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
		if($row['name'] == 'gewicht'){
			$gewicht = $row['value'];
		}
		if($row['name'] == 'bmr'){
			$bmr = $row['value'];
		}				

		}	
	

echo "<center>";		
echo "<table>";
echo "<tr><td style='background-color:#88cbff;'>Metric</td><td style='background-color:#88cbff;'>Waarde</td></tr>";
echo "<tr><td>Gewicht</td><td><input class='metric' id='gewicht' onchange='update_settings(\"gewicht\")' value='$gewicht'/> kg</td></tr>";
echo "<tr><td>BMR</td><td><input class='metric' id='bmr' onchange='update_settings(\"bmr\")' value='$bmr'/></td></tr>";
echo "</table>";
echo "</center>";
echo "<br>";
echo "<br>";

$sql = "SELECT id, name from size_type order by name";
$result = $conn->query($sql);
$size_types = array();
$size_id_types = array();
while ($row = $result->fetch_assoc()) {
		array_push($size_types, $row['name']);
		array_push($size_id_types, $row['id']);
}
	
$sql = "SELECT 
product.name, 
product.id as product_id,
size_type.name as size_type, 
size_type.id as size_type_id,
product_content.size, 
product_content.fat, 
product_content.fat_s, 
product_content.carbs, 
product_content.sugar, 
product_content.protein, 
product_content.alcohol,
product_content.fiber 

FROM product_content inner join size_type on product_content.size_type_id = size_type.id inner join product on product.id = product_content.product_id order by product.name";
$result = $conn->query($sql);
echo "<center>";		
echo "<table>";
echo "<tr><td style='background-color:#88cbff;'>Product</td><td style='background-color:#88cbff;'>Eenheid</td><td style='background-color:#88cbff;'>Vet</td><td style='background-color:#88cbff;'>Waarvan verzadigd</td><td style='background-color:#88cbff;'>Koolhydraten</td><td style='background-color:#88cbff;'>Waarvan suiker</td><td style='background-color:#88cbff;'>Eiwitten</td><td style='background-color:#88cbff;'>Alcohol</td><td style='background-color:#88cbff;'>Vezels</td></tr>";
while ($row = $result->fetch_assoc()) {
	$name = $row['name'];
	$product_id = $row['product_id'];
	$size_type = $row['size_type'];
	$size_type_id = $row['size_type_id'];
	$size = $row['size'];
	$fat = $row['fat'];
	$fat_s = $row['fat_s'];
	$carbs = $row['carbs'];
	$sugar = $row['sugar'];
	$protein = $row['protein'];
	$alcohol = $row['alcohol'];
	$fiber = $row['fiber'];
	echo "<tr><td><input class='name' onchange='update_product($product_id, \"name\")' value='$name'/></td>";
	echo "<td><input class='content' onchange='update_product($product_id, \"size\")' value='$size'/> ";
	echo "<select onchange='update_product($product_id, \"size_type_id\")' value='$size_type_id'/>";
	for($i=0; $i<count($size_types); $i++){
		$type_id = $size_id_types[$i];
		$type = $size_types[$i];
		if($type_id == $size_type_id){
			echo "<option value='$type_id' selected>$type</option>";
		}
		else{
			echo "<option  value='$type_id'>$type</option>";
		}			
	}
	echo "</select>";
	echo "<td><input class='content' onchange='update_product($product_id, \"fat\")' value='$fat'/><td><input class='content' onchange='update_product($product_id, \"fat_s\")' value='$fat_s'/></td><td><input class='content' onchange='update_product($product_id, \"carbs\")' value='$carbs'/></td><td><input class='content' onchange='update_product($product_id, \"sugar\")' value='$sugar'/><td><input class='content' onchange='update_product($product_id, \"protein\")' value='$protein'/></td><td><input class='content' onchange='update_product($product_id, \"alcohol\")' value='$alcohol'/></td><td><input class='content' onchange='update_product($product_id, \"fiber\")' value='$fiber'/></tr>";
}
echo "</table>";
echo "</center>";
echo "<br>";
echo "<br>";


?>
