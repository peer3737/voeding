<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script>
		
function new_product(){
	product = document.getElementById("product").value;
	amount = document.getElementById("amount").value;
	vet = document.getElementById("vet").value;
	koolhydraat = document.getElementById("koolhydraat").value;
	eiwit = document.getElementById("eiwit").value;
	alcohol = document.getElementById("alcohol").value;
	size_type = document.getElementById("size_type").value;
	if(document.getElementById("is_drink").checked){
		category = 3;
	}
	else{
		category = 1;
	}
	

	url = "send_new_product.php?prod="+product+"&fat="+vet+"&carbs="+koolhydraat+"&protein="+eiwit+"&alcohol="+alcohol+"&amount="+amount+"&size_type="+size_type+"&category="+category
	console.log(url);
 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("ok")
						location.href="index.php";

					
					
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

function get_size_type(){
	document.getElementById("size_type").innerHTML = ""
	value = document.getElementById("products").value;
	url = "size_type_info.php?id="+value;
 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request was successful, process the response
					var output = xhr.responseText;
					document.getElementById("size_type").innerHTML = output;
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
		
		function update_size_type(){
			if(document.getElementById("is_drink").checked){
				document.getElementById("size_type").value = 2;
				document.getElementById("amount").value = 100;
			}
			else{
				document.getElementById("size_type").value = "";
				document.getElementById("amount").value = "";
			}
		}
		
				
		
</script>
	<style>
	        #products {
            width:50vw;
			
        }
			

		#categories {
            width:35vw;
			height:28px;
			padding-left: 8px;
			font-size:100%;
			background-color:#FFFFFF;
        }
		
		#size_type {
			height:28px;
			padding-left: 8px;
			font-size:100%;
			background-color:#FFFFFF;
        }
		#amount {
            width:35vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#vet {
            width:35vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#koolhydraat {
            width:35vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#eiwit {
            width:35vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#alcohol {
            width:35vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#product {
             width:35vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#new_product {
            width:35vw;
			height:25px;
			padding: 1px;
			font-size:100%;
			
        }
		#is_drink {
			height: 26px;
			width: 26px;
			margin:0;
		}

		</style>
    <title>Basic Responsive Web Design with Image Menu</title>
<?php
if(isset($_GET["n"])){
	$naam = $_GET["n"];
	}
else{
	$naam = "";
	}
if(isset($_GET["u"])){
	$eenheid = $_GET["u"];
	}
else{
	$eenheid = "";
	}
if(isset($_GET["p"])){
	$portiegrootte = $_GET["p"];
	}
else{
	$portiegrootte = "";
	}
if(isset($_GET["v"])){
	$vetten = $_GET["v"];
	}
else{
	$vetten = "";
	}
if(isset($_GET["k"])){
	$koolhydraten = $_GET["k"];
	}
else{
	$koolhydraten = "";
	}
if(isset($_GET["e"])){
	$eiwitten = $_GET["e"];
	}
else{
	$eiwitten = "";
	}
if(isset($_GET["a"])){
	$alcohol = $_GET["a"];
	}
else{
	$alcohol = "";
	}
?>    
</head>
<body>
    <div class="container" id="container">
        <main>
		<center>
		<table>
<tr><td>Drinken</td><td>
			<input type="checkbox" id="is_drink" onclick="update_size_type()"/></td>
			</tr>
			

			<tr><td>Product</td><td>
			<input type="text" id="product" value="<?php echo $naam;?>"/></td>
			</tr>
			<tr><td>Portiegrootte</td><td>
			<input type="text" id="amount" value="<?php echo $portiegrootte;?>"/> <select id="size_type">
			<option value="" selected disabled></option>
			
		<?php
			$servername = "91.184.19.128"; // Change to your MySQL server's hostname or IP address
	$username = "p421706_voeding"; // Replace with your MySQL username
	$password = "d7uef7kX~pnkE"; // Replace with your MySQL password
	$database = "p421706_voeding"; // Replace with your database name

	// Create a connection to the MySQL database
	
			$conn = new mysqli($servername, $username, $password, $database);
						$sql = "SELECT * FROM size_type order by name";
						echo $sql;
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$name = $row["name"];
					$id = $row["id"];
					echo "<option value=\"$id\">$name</option>";

				}
			} 
$conn->close();
?>
</select>
			</td>
			</tr>
			<tr><td>Vetten</td><td>
			<input type="text" id="vet" value="<?php echo $vetten;?>"/> <span>gr per portie</span></td>
			</tr>		
			<tr><td>Koolhydraten</td><td>
			<input type="text" id="koolhydraat" value="<?php echo $koolhydraten;?>"/> <span>gr per portie</span></td>
			</tr>
			<tr><td>Eiwitten</td><td>
			<input type="text" id="eiwit" value="<?php echo $eiwitten;?>"/> <span>gr per portie</span></td>
			</tr>			
			<tr><td>Alcohol</td><td>
			<input type="text" id="alcohol" value="<?php echo $alcohol;?>"/> <span>gr per portie</span></td>
			</tr>			


			<tr><td></td><td><button  type="button" onclick="new_product()" id="new_product" >Maak nieuw product aan</button></td></tr>
			</table>
			</center>
        </main>
    </div>

</body>
</html>
