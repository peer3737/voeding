<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
jQuery(document).ready(checkContainer);

function checkContainer () {
  if ($('#container').is(':visible')) { //if the container is visible on the page
   $(".select2").select2();
    var today = new Date().toISOString().substr(0, 10);
	update_products();
 document.getElementById("date").value = today;
  } else {
    setTimeout(checkContainer, 50); //wait 50 ms, then try again
  }
}


function update_products(){
	//value = document.getElementById("categories").value;
	document.getElementById("size_type").innerHTML = ""
	url = "load_products.php?id=";
	//alert(url);
 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request was successful, process the response
					var output = xhr.responseText;
					var product_select = document.getElementById("products");
					var output_array = output.split(";");
					while (product_select.options.length > 0) {
						product_select.remove(0);
					}
					
					var option = document.createElement("option");
					option.value = "";
					option.text = "";
					option.disabled = true;
					option.selected = true;
					product_select.appendChild(option);
					
					for(var i=0; i<output_array.length/2; i++){
						id_pos = 2*i;
						name_pos = id_pos + 1;
						size_type_pos = id_pos + 2;
						var id = output_array[id_pos];
						var name = output_array[name_pos];
						

						// Create a new option element
						var option = document.createElement("option");

						// Set the value and text for the option
						option.value = id;
						option.text = name;
						product_select.appendChild(option);
						

					}
					console.log(product_select);
                } else {
                    // Request failed, handle the error
                    console.error('Request failed with status:', xhr.status);
                }
				document.getElementById("overlay").style.display = "none";
			document.getElementById("container").style.display = "flex";
            };

            // Set up the onerror event handler (for network errors)
            xhr.onerror = function() {
                console.error('Network error occurred');
            };

            // Send the GET request
            xhr.send();

        }
		
		
function send_update(){
	product_id = document.getElementById("products").value;
	amount = document.getElementById("amount").value;
	date = document.getElementById("date").value;
	selected_element = document.getElementById("products");
	var selected_option = selected_element.options[selected_element.selectedIndex];
	var option_text = selected_option.text; 
	if (option_text.startsWith("--") && option_text.endsWith("--")){
	url = "send_update.php?prod_id="+product_id+"&amount="+amount+"&date="+date+"&meal=1";
	}
	else{
		url = "send_update.php?prod_id="+product_id+"&amount="+amount+"&date="+date+"&meal=0";
	}
	//alert(url);
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
	selected_element = document.getElementById("products");
	var selected_option = selected_element.options[selected_element.selectedIndex];
	var option_text = selected_option.text; 
	if (option_text.startsWith("--") && option_text.endsWith("--")){
		document.getElementById("eenheid_text").style.display = "none";
		document.getElementById("waarden_text").style.display = "none";
		document.getElementById("size_type").style.display = "none";
		document.getElementById("choices").style.display = "none";
	}
	
	
	else{
			document.getElementById("eenheid_text").style.display = "initial";
		document.getElementById("waarden_text").style.display = "initial";
		document.getElementById("size_type").style.display = "initial";
		document.getElementById("choices").style.display = "initial";
	value = document.getElementById("products").value;

	url = "size_type_info.php?id="+value;
	console.log(url);
 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request was successful, process the response
					var output = xhr.responseText;
					document.getElementById("size_type").innerHTML = output.split(";")[1];
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
			get_choices();
		
			}
        }
		
function get_choices(){
	document.getElementById("size_type").innerHTML = ""
	value = document.getElementById("products").value;
	url = "history.php?id="+value;
 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request was successful, process the response
					var output = xhr.responseText;
					document.getElementById("choices").innerHTML = output;
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
	<style>
	        #products {
            width:40vw;
			
        }
			
	        #categories {
            width:40vw;
			
        }
		#amount {
            width:10vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#products2 {
             width:30vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#send_update {
            width:30vw;
			height:25px;
			padding: 1px;
			font-size:100%;
			
        }
		#date {
            width:30vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
			
        }
		#overlay {
  position: absolute; /* Sit on top of the page content */
  display: flex;
  width: 100%; /* Full width (cover the whole page) */
  height: 100%; /* Full height (cover the whole page) */
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: 0; 
  justify-content: center;
  align-items: center;
  background-color: rgba(0,0,0,0.5); /* Black background with opacity */
  z-index: 2; /* Specify a stack order in case you're using a different order for other elements */

}
    .loader {
      border: 8px solid #f3f3f3; 
      border-radius: 50%;
      border-top: 8px solid #3498db; 
      width: 60px;
      height: 60px;
      /* Apply the animation here */
      animation: spin 2s linear infinite; 
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }	
		</style>
    <title>Basic Responsive Web Design with Image Menu</title>
    
</head>
<body>
<div id="overlay"><div class="loader"></div></div>
    <div class="container" id="container">
        <main>
		<center>
		<table>
		<!--<tr><td>Categorie</td><td>
		<select id="categories" class="select2" onchange="update_products()">
		<option value="" selected disabled></option>-->
		<?php
			/*
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
			
			$sql = "SELECT * FROM category order by name";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$name = ucfirst($row["name"]);
					$id = $row["id"];
					echo "<option value=\"$id\">$name</option>";

				}
			} 
$conn->close();*/
		?>
		

			
			  

			</select></td>
<tr><td style="padding:5px;">Product</td><td style=" display: flex;">
					
			
			<select id="products" class="select2"  onchange="get_size_type()">


			</select></td></tr>
			<tr><td style="padding:5px;" id="eenheid_text">Grootte eenheid</td><td style="padding:5px;">
			<span id="size_type"/></td>
			</tr>	
			<tr><td style="padding:5px;" id="waarden_text">Laatste waarden (max 10)</td><td style="padding:5px;">
			<span id="choices"/></td>
			</tr>
			<tr><td style="padding:5px;">Aantal eenheden</td><td style="padding:5px;">
			<input type="text" id="amount"/></td>
			</tr>
			
			<tr><td style="padding:5px;">Datum</td><td style="padding:5px;">
			<input type="date" id="date"/></td>
			</tr>
			<tr><td style="padding:5px;"></td><td style="padding:5px;"><button style="width:50vw;" type="button" onclick="send_update()" id="send_update" >Werk totalen bij</button></td></tr>
			</table>
			</center>
        </main>
    </div>

</body>
</html>
