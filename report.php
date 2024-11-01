<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



<script>

function show_daily(x){
	var date = x.id;
	url = "daily.php?d="+date;
	//alert(url);
	var xhr = new XMLHttpRequest();
	
		// Configure the GET request
		xhr.open("GET", url, true);

		// Set up the onload event handler
		xhr.onload = function() {
			if (xhr.status === 200) {
				document.getElementById("container").innerHTML = xhr.responseText;
	
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


function update_sport(x){
	var id = x.id

	var date = id.slice(10, 20);
	var distance = x.value;
	url = "sport.php?date="+date+"&distance="+distance;
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

function toggle_expand(class_name){
	for(var i=0; i<document.getElementsByClassName(class_name).length; i++){
		if(document.getElementsByClassName(class_name)[i].style.display == 'table-row'){
			document.getElementsByClassName(class_name)[i].style.display = 'none';	
		}
		else{
				document.getElementsByClassName(class_name)[i].style.display = 'table-row';
		}
	}
}

function update_single_content(id){
	value = document.getElementById(id).value;
		url = "update_single_content.php?id="+id+"&content="+value;
		console.log(url);
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

</script>			
		
</script>
	<style>

		td{
			border:1px solid black;
			padding:4px;
			font-family:Arial;
			font-size: 10pt;
			
		}

		
tr.bonus td {
  padding-top: 10px;
  border:0;
  height:20px;
}			table{
			border-collapse:collapse;
			max-width:100%;
			min-width:30vw;
		}

		</style>
    <title>Basic Responsive Web Design with Image Menu</title>
    
</head>
<body>
    <div class="container" id="container">


		<?php

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
		
		$date_array = array();
		$number_of_days = 7;
		$current_date = date("Y-m-d");
		$period = date("Y-m-d", strtotime($current_date . " -$number_of_days days"));
		$sql = "SELECT DISTINCT(date) as date FROM content where date > '$period' order by date desc";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			array_push($date_array, $row['date']);
		}


		$sql = "SELECT product.id, product.name, product_content.size, product_content.fat, product_content.fat_s, product_content.sugar, product_content.fiber, product_content.carbs, product_content.protein, product_content.alcohol, product_content.salt, product_content.category_id from product_content inner join product on product.id = product_content.product_id";
		$products = [];
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$id = $row["id"];
				$name = $row["name"];
				$size = $row["size"];
				$fat = $row["fat"];
				$carbs = $row["carbs"];
				$protein = $row["protein"];
				$alcohol = $row["alcohol"];
				$fat_s = $row["fat_s"];
				$sugar = $row["sugar"];
				$fiber = $row["fiber"];
				$salt = $row["salt"];
				$category = $row["category_id"];
				$products[$id] = [
					"name" => $name,
					"size" => $size,
					"fat" => $fat,
					"carbs" => $carbs,
					"protein" => $protein,
					"alcohol" => $alcohol,
					"fat_s" => $fat_s,
					"sugar" => $sugar,
					"fiber" => $fiber,
					"salt" => $salt,
					"category" => $category
				];
				
			}
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
				if($row['name'] == 'alcohol'){
					$macro_alcohol = $row['value'];
				}
				if($row['name'] == 'vetten'){
					$macro_fat = $row['value'];
				}	
				if($row['name'] == 'koolhydraten'){
					$macro_carbs = $row['value'];
				}
				if($row['name'] == 'eiwitten'){
					$macro_protein = $row['value'];
				}	
		}		
echo "<center><table>";
echo "<tr class=\"bonus\"><td colspan='4'></td></tr>";
			
		for($i=0; $i<count($date_array); $i++){
			$date = $date_array[$i];
			$sql = "SELECT product_id, amount FROM content where date='$date'";
			$result = $conn->query($sql);
			$total_kcal = 0;
			$total_carbs = 0;
			$total_fat = 0;
			$total_protein = 0;
			$total_alcohol = 0;
			$total_drink = 0;
			$total_salt = 0;
			$total_fiber = 0;
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$id = $row['product_id'];
					$content = $row['amount'];
					$fat = $products[$id]['fat'] * $content;
					$carbs = $products[$id]['carbs'] * $content;
					$protein = $products[$id]['protein'] * $content;
					$alcohol = $products[$id]['alcohol'] * $content;
					$salt = $products[$id]['salt'] * $content;
					$fiber = $products[$id]['fiber'] * $content;
					$total_kcal += $fat*$macro_fat + $carbs*$macro_carbs + $protein*$macro_protein + $alcohol*$macro_alcohol;
					$total_carbs += $carbs;
					$total_fat += $fat;
					$total_protein += $protein;
					$total_alcohol += $alcohol;
					$total_salt += $salt;
					$total_fiber += $fiber;
					if($products[$id]['category'] == 2){
						$total_drink += $products[$id]['size'] * $content;
					}
				}
			}
			
			$sql = "SELECT distance, kcal FROM running where date='$date'";
			$result = $conn->query($sql);
			$counter = $result->num_rows;
			if($counter == 0){
				$distance = 0;
				$kcal = 0;
			}
			else{
				while ($row = $result->fetch_assoc()) {
					$distance = $row['distance'];
					$kcal = $row['kcal'];
				}
			}
			$total_fat = round($total_fat);
			$total_carbs = round($total_carbs);
			$total_protein = round($total_protein);
			$total_alcohol = round($total_alcohol);
			$total_alcohol = round($total_alcohol);
			$total_kcal = round($total_kcal);
			$total_salt = round($total_salt,2);
			$total_fiber = round($total_fiber,2);

			$fat_kcal = $total_fat * 9;
			$carbs_kcal = $total_carbs * 4;
			$protein_kcal = $total_protein * 4;
			$alcohol_kcal = $total_alcohol * 7;
			$fat_percentage = round($fat_kcal / $total_kcal * 100,1);
			$carbs_percentage = round($carbs_kcal / $total_kcal * 100,1);
			$protein_percentage = round($protein_kcal / $total_kcal * 100,1);
			$alcohol_percentage = round($alcohol_kcal / $total_kcal * 100,1);
			$print_date = substr($date,8,2).'-'.substr($date,5,2).'-'.substr($date,0,4);
			$resultaat = $total_kcal - $bmr - $kcal;
			echo "<tr><td colspan='4'><center><a style='color:#FF0000; text-decoration:none;' id='$date' href='javascript:void(0)' onclick=show_daily(this);return false'>$print_date</a></center></tr>";
			echo "<tr><td colspan='4' style='background-color:#DDDDDD; color:#000000;'><b>Macro's (gr / kcal / %)</b></td></tr>";
		
			echo "<tr><td style='background-color:#000063; color:#FFFFFF;'>Vet</td><td style='background-color:#036300; color:#FFFFFF;'>Koolhydraat</td><td style='background-color:#865d00; color:#FFFFFF;'>Eiwit</td><td style='background-color:#5f0082; color:#FFFFFF;'>Alcohol</td></tr>";
			echo "<tr></td><td style='background-color:#addcff;'>$total_fat</td><td style='background-color:#b2ffb0;'>$total_carbs</td><td style='background-color:#ffe3a3;'>$total_protein</td><td style='background-color:#eec2ff;'>$total_alcohol</td></tr>";

			echo "<tr><td style='background-color:#addcff;'>$fat_kcal</td><td style='background-color:#b2ffb0;'>$carbs_kcal</td><td style='background-color:#ffe3a3;'>$protein_kcal</td><td style='background-color:#eec2ff;'>$alcohol_kcal</td></tr>";
			echo "<tr><td style='background-color:#addcff;'>$fat_percentage</td><td style='background-color:#b2ffb0;'>$carbs_percentage</td><td style='background-color:#ffe3a3;'>$protein_percentage</td><td style='background-color:#eec2ff;'>$alcohol_percentage</td></tr>";
			echo "<tr><td style='background-color:#000000; color:#FFFFFF;'>Totaal kcal</td><td colspan='3' style='background-color:#FFFFFF; color:#000000;'>$total_kcal</td></tr>";
			echo "<tr><td style='background-color:#222222; color:#FFFFFF;'>Vocht (ml) / Vezels (gr) / Zout (gr)</td><td colspan='3' style='background-color:#FFFFFF; color:#000000;>$total_kcal</td><td style='background-color:#FFFFFF; color:#000000;>$total_drink / $total_fiber / $total_salt</td></tr>";
			echo "<tr><td style='background-color:#444444; color:#FFFFFF;'>km</td><td colspan='3' style='background-color:#FFFFFF; color:#000000;'><input type='text' style='width:40px;' id='hardlopen_$date' value='$distance' onchange='update_sport(this)'/></td></tr>";
			echo "<tr><td style='background-color:#666666; color:#FFFFFF;'>Result</td><td colspan='3' style='background-color:#FFFFFF; color:#000000;'>$resultaat</td></tr>";
			echo "<tr class=\"bonus\"><td colspan='4'></td></tr>";
		}	
		echo "</table></center>";
		echo "<br>";
?>
		
	</div>
</body>
</html>
