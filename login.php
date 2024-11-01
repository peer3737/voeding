<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_hash = hash('sha256', $password);
	$properties = parse_ini_file("db.properties"); 

	// Database connection details from the properties file
	$servername = $properties['servername'];
	$db_username = $properties['username'];
	$db_password = $properties['password'];
	$dbname = $properties['dbname'];
	$conn = new mysqli($servername, $db_username, $db_password, $dbname);
	//$conn = new mysqli("localhost", "your_db_user", "your_db_password", "ah");
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	// 3. Check if username already exists
	$stmt = $conn->prepare("SELECT * FROM user WHERE username = ? and password_hash = ? ");
	$stmt->bind_param("ss", $username, $password_hash);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows == 1) {
		$row = $result->fetch_assoc(); 
		$user_id = $row['id'];
		$user_status = $row['status'];
		$user_name = $row['username'];
		$user_password_hash = $row['password_hash'];
		if ($user_status == 1 && $user_name = $username && $user_password_hash == $password_hash){
			session_start();

			$_SESSION['username'] = $username;
			$_SESSION['user_id'] = $user_id;
			$_SESSION['last_activity'] = time();
			header("Location: index.php");
			exit();
		}
		else {
			echo "wrong credentials";
			//header("Location: login.php");
			exit();
		}
	}
	else {
		//header("Location: login.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f5f5f5;
    }

    .container {
      background-color: #fff;
      padding: 30px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      width: 350px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
      border-radius: 4px;
    }

    button:hover {
      opacity: 0.8;
    }

    .error {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
 <div class="container">
    <h2>Login</h2>
    <?php if (isset($error_message)): ?>
      <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="">   

      <input type="text" name="username" value="petervanelsacker@gmail.com" placeholder="Username" required>
      <input type="password" name="password" value="Hallo123" placeholder="Password" required>   

      <button type="submit">Login</button>
    </form>
    <p   
 style="text-align: center;">
      <a href="register.php">Create new account</a> 
    </p>
  </div>

</body>
</html>