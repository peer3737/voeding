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
<head>
	<style>

		td{
			border:1px solid black;
			padding:6px;
		}
		td.expand1 {
			border-left:1px solid black;
			border-right:0;
			border-top:0;
			border-bottom:0;
			background-color:#FFFFFF;
		}
		td.expand2 {
			border-left:0;
			border-right:1px solid black;
			border-top:0;
			border-bottom:0;
			background-color:#FFFFFF;
		}
		table{
			border-collapse:collapse;
			max-width:100vw;
		}
		input{
			width:30px;
			font-size:10pt;
		}
tr.bonus td {
  border:0;
  height:5px;
}
		</style>


	
</head>
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
header('Content-type: text/html; charset=UTF-8');

$date = $_GET['d'];

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


$result_array = array();
$total_array = array();

$sql = "select
product.id as id, 
product.name as name, 
product_content.size*content.amount as content, 
content.amount as single_content,
content.id as content_id,
product_content.size as product_size,
size_type.name as size_type,
product_content.fat*content.amount as fat, 
product_content.fat_s*content.amount as fat_s, 
product_content.carbs*content.amount as carbs, 
product_content.sugar*content.amount as sugar, 
product_content.protein*content.amount as protein, 
product_content.alcohol*content.amount as alcohol,
product_content.fiber*content.amount as fiber,
product_content.salt*content.amount as salt
from product_content inner join product on product.id = product_content.product_id inner join content on product.id = content.product_id inner join size_type on size_type.id = product_content.size_type_id
where date='$date' and product_content.user_id=$user_id order by product.name";
//echo $sql;
$total_fat = 0;
$total_fat_s = 0;
$total_protein = 0;
$total_carbs = 0;
$total_sugar = 0;
$total_alcohol = 0;
$total_fiber = 0;
$total_salt = 0;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$id = $row["id"];
		$name = $row["name"];
		$content = round($row["content"],2);
		$single_content = round($row["single_content"],2);
		$size = $row["size_type"];
		$product_size = $row["product_size"];
		$content_id = $row["content_id"];
		$fat = round($row["fat"],2);
		$fat_s = round($row["fat_s"],2);
		$carbs = round($row["carbs"],2);
		$sugar = round($row["sugar"],2);
		$protein = round($row["protein"],2);
		$alcohol = round($row["alcohol"],2);
		$fiber = round($row["fiber"],2);
		$salt = round($row["salt"],2);
		$total_fat += $fat;
		$total_fat_s += $fat_s;
		$total_protein += $protein;
		$total_carbs += $carbs;
		$total_sugar += $sugar;
		$total_alcohol += $alcohol;
		$total_fiber += $fiber;
		$total_salt += $salt;
		$entry = array($id, $name, $content, $size, $fat, $fat_s, $carbs, $sugar, $protein, $alcohol, $product_size, $single_content, $content_id, $fiber, $salt);
		array_push($result_array, $entry);
	}
}


foreach ($result_array as $result){
	$check = 0;
	for($i=0; $i<count($total_array); $i++){
		if($total_array[$i][0] == $result[0]){
			$check = 1;
			$total_array[$i][2] += $result[2];
			$total_array[$i][4] += $result[4];
			$total_array[$i][5] += $result[5];
			$total_array[$i][6] += $result[6];
			$total_array[$i][7] += $result[7];
			$total_array[$i][8] += $result[8];
			$total_array[$i][9] += $result[9];
			$total_array[$i][13] += $result[13];
			$total_array[$i][14] += $result[14];
		}
	}
	if($check == 0){
		$entry = array($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8], $result[9], $result[10], $result[11], $result[12], $result[13], $result[14]); 
		array_push($total_array, $entry);
	}	
	else{
		$check = 0;
	}
}

$output = "<center><table>";
/*
$output.="<tr><td colspan=\"2\" style='cursor:pointer;background-color:#DDDDDD;' onclick='toggle_expand(\"$id\")'><b>Totaal</b></td></td></tr>";

	$output.="<tr><td style='background-color:#85eeff;'>Vetten</td><td><b>$total_fat</b></td></tr>";
	$output.="<tr><td style='background-color:#85eeff;'>Waarvan verzadigd</td><td><b>$total_fat_s</b></td>";
	$output.="<tr><td style='background-color:#85eeff;'>Koolhydraten</td><td><b>$total_carbs</b></td></tr>";
	$output.="<tr><td style='background-color:#85eeff;'>Waarvan suiker</td><td><b>$total_sugar</b></td></tr>";
	$output.="<tr><td style='background-color:#85eeff;'>Eiwitten</td><td><b>$total_protein</b></td></tr>";
	$output.="<tr><td style='background-color:#85eeff;'>Alcohol</td><td><b>$total_alcohol</b></td></tr>";
	$output.="<tr><td style='background-color:#85eeff;'>Vezels</td><td><b>$total_fiber</b></td></tr>";
	$output.="<tr><td style='background-color:#85eeff;'>Zout</td><td><b>$total_salt</b></td></tr>";
	*/
$output.="<tr><td style='background-color:#001266;color:#EDEDED;'>Product</td><td style='background-color:#001266;color:#EDEDED;'>Hoeveelheid</td></tr>";

foreach ($total_array as $result){

	$id = $result[0];
	$name = $result[1];
	$content = $result[2];
	$size = $result[3];
	$fat = $result[4];
	$fat_s = $result[5];
	$carbs = $result[6];
	$sugar = $result[7];
	$protein = $result[8];
	$alcohol = $result[9];
	$product_size = $result[10];
	$single_content = $result[11];
	$content_id = $result[12];
	$fiber = $result[13];
	$salt = $result[14];
	$output.="<tr><td style='cursor:pointer;background-color:#DDDDDD;' onclick='toggle_expand(\"$id\")'>$name</td><td>$content $size</td></td></tr>";

		

	for($i=0; $i<count($result_array); $i++){
		//print_r($result_array[$i]);
		if($result_array[$i][0] == $id){
			//print_r($result_array[$i]);
			/*
			$name = $result_array[$i][1];

			$fat = $result_array[$i][4];
			$fat_s = $result_array[$i][5];
			$carbs = $result_array[$i][6];
			$sugar = $result_array[$i][7];
			$protein = $result_array[$i][8];
			$alcohol = $result_array[$i][9];
			$single_content = $result_array[$i][11];
			$product_size = $result_array[$i][10];
			
			$fiber = $result_array[$i][13];
			$salt = $result_array[$i][14];
			*/
						//$name = $result_array[$i][1];
			$content = $result_array[$i][2];
			
			$size = $result_array[$i][3];
			$content_id = $result_array[$i][12];
			$single_content = $result_array[$i][11];
			$product_size = $result_array[$i][10];
			$visible_value = $single_content * $product_size;


			$output.="<tr class='$id' style='display:none;'><td>Portie</td><td><input id=$content_id onchange='update_single_content($content_id)' value='$visible_value'/> $size</td></td></tr>";


		}
	}
	
				$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">Vetten</td><td class=\"expand2\">$fat</td></tr>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">&nbsp;&nbsp;-Waarvan verzadigd</td><td class=\"expand2\">$fat_s</td>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">Koolhydraten</td><td class=\"expand2\">$carbs</td></tr>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">&nbsp;&nbsp;-Waarvan suiker</td><td class=\"expand2\">$sugar</td></tr>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">Eiwitten</td><td class=\"expand2\">$protein</td></tr>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">Alcohol</td><td class=\"expand2\">$alcohol</td></tr>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">Vezels</td><td class=\"expand2\">$fiber</td></tr>";
			$output.="<tr class='$id' style='display:none;'><td class=\"expand1\">Zout</td><td class=\"expand2\">$salt</td></tr>";
}
$output.= "<tr class=\"bonus\"><td colspan='2'></td></tr>";

$output.="</table></center>";

	#save_cache($cache_file, $output);
	echo $output;
$conn->close();
}
?>
