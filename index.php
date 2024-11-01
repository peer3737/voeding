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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedingsmanager</title>
	 <link rel="icon" type="image/x-icon" href="icons/favicon.ico">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
	<script src="supporting-js.js"></script>
	
<script>


</script>
    <style>
        /* Reset some default styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        /* Set a background color */
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        /* Add a container with a max-width to center content */
        #container {
            max-width: 100vw;
            margin: 0 auto;
            padding-top: 10px;
			display: block;
        }

        /* Style the header */
        header {
            color: #000000;
            text-align: center;
            padding: 0px;
        }

        /* Style the main content */
        main {
            padding: 0px;
        }

        /* Style the footer */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        /* Create a simple responsive layout */
        @media screen and (max-width: 600px) {
            .container {
                padding-top: 10px;
            }
        }

        /* Style the top menu */
        nav {
            display: flex;
            justify-content: space-around;
            padding: 0px 0;
        }
		
		nav table {
			border:0px solid black;
			border-collapse: collapse;
			
		}
		nav td {
			border:1px solid black;
			width:50vw;
			padding-top	:10px;
		}
		nav img {
			width:10vw;
		}


    </style>
</head>
<body>
    <header>
        <nav>
			<table>
				<tr>
					<td><a  href="javascript:void(0)" onclick="loadContent('add_record.php');return false"><img src="icons/add_food.png" target="#container"></a></td>
					<td><a  href="javascript:void(0)" onclick="loadContent('add_drink.php');return false"><img src="icons/add_drink.png" target="#container"></a></td>
					<td><a  href="javascript:void(0)" onclick="loadContent('new_product2.php');return false"><img src="icons/new_product.png" target="#container"></a></td>
					<td><a  href="javascript:void(0)" onclick="loadContent('add_meal.php');return false"><img src="icons/meal.png" target="#container"></a></td>
					<td><a  href="javascript:void(0)" onclick="loadContent('settings.php');return false"><img src="icons/settings.png" target="#container"></a></td>
					<td><a  href="javascript:void(0)" onclick="loadContent('report.php');return false"><img src="icons/report.png" target="#container"></a></td>



				</tr>
			</table>
        </nav>
    </header>
    <div class="container" id="container">

    </div>
    <footer>
        &copy; Voedingsmanager
    </footer>
</body>
</html>
