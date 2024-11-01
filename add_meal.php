<!DOCTYPE html>
<html lang="en">
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

	update_products();

  } else {
    setTimeout(checkContainer, 50); //wait 50 ms, then try again
  }
}


function update_products(){
	//value = document.getElementById("categories").value;
	//document.getElementById("size_type").innerHTML = ""
	url = "load_products.php?id=&t=meal";
	console.log(url);
	
 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request was successful, process the response
					var output = xhr.responseText;
					//console.log(output);
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
		
		
function create_meal(){
	product_ids = document.getElementsByClassName("product_id");
	units = document.getElementsByClassName("amount");
	meal_name = document.getElementById("meal_name").value;
	url = "create_meal.php?meal_name="+meal_name;
	for(i=0; i<product_ids.length; i++){
		combo = "&product_id_"+i+"="+product_ids[i].value+"&units_"+i+"="+units[i].value;
		url += combo;
	}
	console.log(url);
	//alert(url)

 var xhr = new XMLHttpRequest();

            // Configure the GET request
            xhr.open("GET", url, true);

            // Set up the onload event handler
            xhr.onload = function() {
                if (xhr.status === 200) {
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
	//document.getElementById("size_type").innerHTML = ""
		var selectedValues = $('#products').val();
		var table = document.getElementById("meal")

		  var rowCount = table.rows.length;

		  // Iterate through the rows (excluding first and last) and remove them
		  for (var i = rowCount - 2; i > 1; i--) {
			table.deleteRow(i);
		  }
		  urls = [];
        for(i=0; i<selectedValues.length; i++){
			var j = i;
			value = selectedValues[i]
					
			  var table = document.getElementById("meal");
			  var rowCount = table.rows.length;
			  var row = table.insertRow(rowCount - 1); // Insert at position n-1
			  var cell1 = row.insertCell(0);
			  cell1.id = "product_"+j;
			  cell1.classList.add("product_name");
			  cell1.style.display="none";

			  rowCount++;
			  var row = table.insertRow(rowCount - 1); // Insert at position n-1
			  var cell2 = row.insertCell(0);
			  var cell3 = row.insertCell(1);
			  cell2.innerHTML = "Grootte eenheid";
			  cell3.innerHTML = "<span class='size_type' id='size_type_"+j+"'/>";
			  rowCount++;
			  var row = table.insertRow(rowCount - 1); // Insert at position n-1
			  var cell4 = row.insertCell(0);
			  var cell5 = row.insertCell(1);
			  cell4.innerHTML = "Product ID";
			  cell5.innerHTML = "<input type='text' name='product_id_"+j+"' class='product_id' id='product_id_"+j+"'/>";
			  row.style.display = "none";
			  rowCount++;
			  var row = table.insertRow(rowCount - 1); // Insert at position n-1
			  var cell6 = row.insertCell(0);
			  var cell7 = row.insertCell(1);
			  cell6.innerHTML = "Aantal eenheden";
			  cell7.innerHTML = "<input type='text' name='amount_"+j+"' class='amount'/>";		
			
		url = "size_type_info.php?id="+value;
		urls.push(url)
		

			
        }
		
		  var index = 0;

	  function sendNextRequest() {
		if (index < urls.length) {
		  makeHttpRequest(urls[index], function (error, response) {
			if (error) {
			  console.error("Request failed:", error);
			} else {
			}

			index++;
			sendNextRequest();
		  }, index);
		}
	  }

	  sendNextRequest();
	  
	for(i=0; i<urls.length; i++){
		document.getElementById("product_"+i).style.display="block"
	}
	
}



function makeHttpRequest(url, callback, j){
	 var xhr = new XMLHttpRequest();

				// Configure the GET request
				xhr.open("GET", url, true);

				// Set up the onload event handler
				xhr.onload = function() {
					if (xhr.status === 200) {
						callback(null, xhr.responseText);
						// Request was successful, process the response
						var output = xhr.responseText;
						
						var product_name = output.split(";")[0];
						var size = output.split(";")[1];
						var product_id = output.split(";")[2];
						document.getElementById("product_"+j).innerHTML = "<b>"+product_name+"</b>";
						document.getElementById("product_id_"+j).value = product_id;
						document.getElementById("size_type_"+j).innerHTML = size;

						//document.getElementById("size_type").innerHTML = output;
					} else {
						// Request failed, handle the error
						callback("Error: " + xhr.status);
						
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
		input.amount {
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
		#meal_name {
			width:30vw;
			height:22px;
			padding-left: 8px;
			font-size:100%;
		}
		</style>
    <title>Basic Responsive Web Design with Image Menu</title>
    
</head>
<body>
    <div class="container" id="container">
        <main>
		<center>
		<table id="meal">

			  

			</select></td>
			<tr><td>Naam maaltijd</td><td><input id="meal_name" type="text"/></td></tr>
<tr><td>Producten</td><td style=" display: flex;">
					
			
			<select id="products" class="select2" onchange="get_size_type()" multiple>


			</select></td></tr>
			
			

			
			<tr><td style="padding:5px;"></td><td style="padding:5px;"><button style="width:50vw;" type="button" onclick="create_meal()" id="create_meal" >Maak maaltijd aan</button></td></tr>
			</table>
			</center>
        </main>
    </div>

</body>
</html>
