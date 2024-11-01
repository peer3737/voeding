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
<html><head><style>

body {
  font-family: Calibri;

}
table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    font-family: Calibri;
    box-sizing: border-box;
    position: relative;
}
th, td.product {
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
    position: relative;
    box-sizing: border-box;

}
th {
    background-color: #B4E4FF;
    font-weight: normal;
}
.collapsible {
    cursor: pointer;
    font-weight: bold;
}
.hidden {
    display: none;
}
.titlelevel {
    min-width:480px;
    background-color:#FFFFFF;
}
.coloring0 {
    background-color:#FFFFFF;
}
.coloring1 {
    background-color:#F5F5F5;
}
.coloring2 {
    background-color:#E8E8E8;
}
.coloring3 {
    background-color:#DCDCDC;
}
.coloring4 {
    background-color:#D3D3D3;
}
.coloring5 {
    background-color:#C8C8C8;
}
.coloring6 {
    background-color:#BEBEBE;
}
.coloring7 {
    background-color:#B0B0B0;
}
.coloring8 {
    background-color:#A8A8A8;
}
.coloring9 {
    background-color:#989898;
}
.coloring10 {
    background-color:#888888;
}
.coloring11 {
    background-color:#787878;
}
.coloring12 {
    background-color:#696969;
}
.coloring13 {
    background-color:#606060;
}
.coloring14 {
    background-color:#505050;
}

.toggle-symbol {
    display: inline-flex; /* or inline-block */
    border: 1px solid black;
    width: 20px;
    height: 20px;
    justify-content: center;
    align-items: center;
    font-size: 18px; /* Adjust as needed */
}
.notused {
    text-decoration:Line-through;
    opacity:20%;
}
button {
    margin-bottom: 10px;
}

.table-container {
    width: 100%;
}

.resizer {
    position: absolute;
    top: 0;
    right: 0;
    width: 5px;
    height: 100%;
    cursor: col-resize;
    user-select: none;
    background-color: transparent;
}
th .resizer {
    height: calc(100% - 8px);
    top: 4px;
}

.attention{
    background-color: #FF0000;
    }
.title {
    background-color:#EDEDED;
    font-size:20pt;
    }
img.star{
	width:20px;
	height:20px;
	cursor:pointer;
}
</style>
<script>

function manual(){
	name = document.getElementById("name").value;
	is_drink = document.getElementById("is_drink").checked;
	portion = document.getElementById("size").value;
	fetch("manual_product.php?name="+name+"&is_drink="+is_drink+"&portion="+portion)  // Replace with your API endpoint
	  .then(response => {
		if (!response.ok) {               // Check if the response is successful
		  throw new Error('Network response was not ok');
		}
		return response;            // Parse the response as JSON (adjust if needed)
	  })
	  .then(data => {
		console.log(data);                // Do something with the received data
	  })
	  .catch(error => {
		console.error('There was a problem with the fetch operation:', error);
	  });
	  }
function update_img(id, prod_id){
	content = document.getElementById(id).src;
	if (content.includes("no_bookmark_star.png")){
		document.getElementById(id).src = "bookmark_star.png"
		var bookmark = 1
	}
	else{
		document.getElementById(id).src = "no_bookmark_star.png"
		var bookmark = 0
	}
	fetch("update_bookmark.php?id="+prod_id+"&bookmark="+bookmark)  // Replace with your API endpoint
	  .then(response => {
		if (!response.ok) {               // Check if the response is successful
		  throw new Error('Network response was not ok');
		}
		return response;            // Parse the response as JSON (adjust if needed)
	  })
	  .then(data => {
		console.log(data);                // Do something with the received data
	  })
	  .catch(error => {
		console.error('There was a problem with the fetch operation:', error);
	  });
	
	}
</script>
</head>


<body>

		
		
	<?php
	
	$properties = parse_ini_file("db.properties"); 

	// Database connection details from the properties file
	$servername = $properties['servername'];
	$username = $properties['username'];
	$password = $properties['password'];
	$dbname = $properties['dbname'];
	$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection

	if(isset($_GET['query'])){
			echo "<table>
		<thead>
			<tr><td class=\"product\" colspan=\"6\"><form action=\"/new_product2.php\" method=\"GET\"><input type=\"text\" width=\"100\" name=\"query\"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"Zoeken\"></form></td></tr>
			<tr><td class=\"product\" colspan=\"2\"><b>Voeg handmatig product toe<b></td></tr>
			<tr><th>Naam</th><td class=\"product\"><input type=\"text\" id=\"name\"/></td></tr>
			<tr><th>Drinken</th><td class=\"product\"><input type=\"checkbox\" id=\"is_drink\"/></td></tr>
			<tr><th>Portiegrootte (g / ml)</th><td class=\"product\"><input type=\"text\" id=\"size\"/></td></tr>
			<tr><td class=\"product\"></td><td class=\"product\"><button type=\"button\" onclick=\"manual()\">Voeg handmatig toe</td></tr>
			
			<tr><td class=\"product\"></td><th>Naam</th><th>Hoeveelheid</th><th>Prijs</th><th>Aanbieding</th><th>Bijgewerkt op</th></tr>
		</thead>
		<tbody>";
		$query = $_GET['query'];
		$url = "https://api.ah.nl/mobile-auth/v1/auth/token/anonymous";
		$headers = array(
			"Host: api.ah.nl",
			"x-dynatrace: MT_3_4_772337796_1_fae7f753-3422-4a18-83c1-b8e8d21caace_0_1589_109",
			"x-application: AHWEBSHOP",
			"user-agent: Appie/8.8.2 Model/phone Android/7.0-API24",
			"content-type: application/json; charset=UTF-8"
		);
		
		$data = array(
			"clientId" => "appie"
		);
		$jsonData = json_encode($data);
		$context = stream_context_create([
			'http' => [
				'method' => 'POST',
				'header' => implode("\r\n", $headers),
				'content' => $jsonData
				
			]
		]);

		// Make the request and get the response
		$response = file_get_contents($url, false, $context);

		// Check for errors
		if ($response === false) {
			$accessToken = "ERROR";
		} else {
			// Process the response
			$responseData = json_decode($response, true);
			$accessToken = $responseData['access_token'];
		}

		
		
		if($accessToken != "ERROR"){
			$url = "https://api.ah.nl/mobile-services/product/search/v2";

			// Parameters
			$params = array(
				"sortOn" => "RELEVANCE",
				"page" => 0,
				"size" => 1000,
				"query" => "$query"
			);
			$fullUrl = $url . '?' . http_build_query($params);
			// Headers
			$headers = array(
				"Host: api.ah.nl",
				"x-dynatrace: MT_3_4_772337796_1_fae7f753-3422-4a18-83c1-b8e8d21caace_0_1589_109",
				"x-application: AHWEBSHOP",
				"user-agent: Appie/8.8.2 Model/phone Android/7.0-API24",
				"content-type: application/json; charset=UTF-8",
				"Authorization: Bearer $accessToken"
			);

			$context = stream_context_create([
				'http' => [
					'method' => 'GET',
					'header' => implode("\r\n", $headers)
				]
			]);		
			//echo implode("\r\n", $headers);
			$response = file_get_contents($fullUrl, false, $context);		
			$responseData = json_decode($response, true);
			$id_values = array();

			foreach ($responseData['products'] as $item) { 
				$id_values[] = $item['webshopId'];
			}
			$id_values = "(" . implode(", ", $id_values) . ")";
			if($id_values == "()"){
				echo "<tr><td class=\"product\" colspan=\"6\">Geen resultaten gevonden voor <b>$query</b></td></tr>";
				exit();
			}
		}
		$sql = "SELECT product.name, main.*, product.id, 1 
		FROM (
			SELECT *, ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY update_date DESC) AS rn
				FROM main
			) AS main
		INNER JOIN product ON product.id = main.product_id
		INNER JOIN bookmark on product.id = bookmark.product_id
		
			WHERE main.rn = 1 and main.product_id in $id_values and bookmark.user_id = $user_id
			UNION ALL
		SELECT product.name, main.*, product.id, 0 
		FROM (
			SELECT *, ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY update_date DESC) AS rn
				FROM main
			) AS main
		INNER JOIN product ON product.id = main.product_id
		
			WHERE main.rn = 1 and main.product_id in $id_values and product.id not in (SELECT product_id from bookmark where user_id = $user_id)";	
			
		}
		else{
						echo "<table>
		<thead>
			<tr><td class=\"product\" colspan=\"6\"><form action=\"new_product2.php\" method=\"GET\"><input type=\"text\" width=\"100\" name=\"query\"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"Zoeken\"></form></td></tr>
			<tr><td class=\"product\" colspan=\"2\"><b>Voeg handmatig product toe<b></td></tr>
			<tr><th>Naam</th><td class=\"product\"><input type=\"text\" id=\"name\"/></td></tr>
			<tr><th>Drinken</th><td class=\"product\"><input type=\"checkbox\" id=\"is_drink\"/></td></tr>
			<tr><th>Portiegrootte (g / ml)</th><td class=\"product\"><input type=\"text\" id=\"size\"/></td></tr>
			<tr><td class=\"product\"></td><td class=\"product\"><button type=\"button\" onclick=\"manual()\">Voeg handmatig toe</td></tr>
		</thead>
		<tbody>";
			$sql = "SELECT product.name, main.*, product.id
			FROM (
				SELECT *, ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY update_date DESC) AS rn
					FROM main
				) AS main
			INNER JOIN product ON product.id = main.product_id
			INNER JOIN bookmark on product.id = bookmark.product_id
				WHERE main.rn = 1 and bookmark.user_id = $user_id";
			}
			//echo $sql;
			$result = $conn->query($sql);
			
			$content_array = array();
			$content_sql = "SELECT product_id from product_content";
			$content_result = $conn->query($content_sql);
			while($row_content = $content_result->fetch_row()) {
				$content_id = $row_content[0];
				$content_array[] = $content_id;
			}
			
			while($row = $result->fetch_row()) {
				$id = $row[2];
				if(in_array($id, $content_array)){
					$color = "#FFA500";
				}
				else{
					$color = "#FFFFFF";
				}
				$name = $row[0];
				$size = $row[3];
				try{
					if(is_numeric($size) === $size){
						$size = is_numeric($size);
					}
				}
				catch (Exception $e){
					$size = $size;
				}

				$unit_type = $row[4];
				$content = "$size $unit_type";
				$base_price = number_format($row[6], 2, ',', '.');
				$unit_size = $row[5];


				$unit_price = number_format($row[7], 2, ',', '.');
				$bonus_price = number_format($row[8], 2, ',', '.');
				$bonus_unit_price = number_format($row[9], 2, ',', '.');
				$bonus_start_date = $row[12];
				$bonus_end_date = $row[13];
				$now = date('Y-m-d');
				$is_bonus = $row[10];
				$bonus_type = $row[11];
				if($unit_type == 'g' or $unit_type == 'kg'){
					$use_unit = True;
					$unit_content = "<span style=\"font-size:12px;\"> ($unit_price/kg)</span>";
				}
				else if($unit_type == 'ml' or $unit_type == 'cl' or $unit_type == 'l'){
					$use_unit = True;
					$unit_content = "<span style=\"font-size:12px;\"> ($unit_price/l)</span>";
				}	
				else{
					$use_unit = False;
					$unit_content = "";
				}
				

				if($is_bonus == 1 && $bonus_start_date <= $now && $bonus_end_date > $now){
					if($use_unit){
						if($unit_type == 'g' or $unit_type == 'kg'){
							$bonus_unit_content = "<span style=\"font-size:12px;\"> ($bonus_unit_price)/kg</span>";
						}
						if($unit_type == 'ml' or $unit_type == 'cl' or $unit_type == 'l'){
							$bonus_unit_content = "<span style=\"font-size:12px;\"> ($bonus_unit_price)/l</span>";
						}
						
					}
					else{
						$bonus_unit_content = "";
					}
					$price = "<s>$base_price $unit_content</s><br>$bonus_price $bonus_unit_content";
					$bonus = $bonus_type;
				}
				else{
					$price = "$base_price$unit_content";
					$bonus = "";
				}
				$laatste_update = $row[14];
				$laatste_update = $row[14];
				$is_bookmark = 1;
				$bookmark_id = $id."_bookmark";
				if($is_bookmark == 0){
					
					$bookmark_td = "<td class=\"product\" style=\"background-color:$color;\"><center><img class=\"star\" id=\"$bookmark_id\" src=\"no_bookmark_star.png\" onclick=\"update_img('$bookmark_id', '$id')\"/></center></td>";
				}
				else {
					$bookmark_td = "<td class=\"product\" style=\"background-color:$color;\"><center><img class=\"star\" id=\"$bookmark_id\" src=\"bookmark_star.png\" onclick=\"update_img('$bookmark_id', '$id')\"/></center></td>";
				}
				
				echo "<tr>$bookmark_td<td class=\"product\"><a target=\"_BLANK\" href=\"product.php?id=$id\">$name</a></td><td class=\"product\">$content</td><td class=\"product\">$price<span style=\"font-size:12px;\"></span></td><td class=\"product\">$bonus</td><td class=\"product\">$laatste_update</td></tr>";
			}
			
	
		?>
		
		</table>
		
		</tbody></body></html>
		
		