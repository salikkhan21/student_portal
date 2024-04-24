<?php
// Initialize variables to hold user input
$name = $reg_no = $password = $confirm_password = "";
 
// Define validation error messages
$name_error = $reg_no_error = $password_error = $confirm_password_error = "";
 
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  // Validate name
  if (empty($_POST["name"])) {
    $name_error = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // Check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $name_error = "Only letters and white space allowed"; 
    }
  }
 
  // Validate reg_no
  if (empty($_POST["reg_no"])) {
    $reg_no_error = "Email is required";
  } else {
    $reg_no = test_input($_POST["reg_no"]);

    if (!filter_var($reg_no, FILTER_VALIDATE_EMAIL)) {
      $reg_no_error = "Invalid email format"; 
    }
  }
  
  // Validate password
  if (empty($_POST["password"])) {
    $password_error = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
    // Check if password is at least 8 characters long
    if (strlen($password) < 8) {
      $password_error = "Password must be at least 8 characters long";
    }
  }
  
  // Validate confirm password
  if (empty($_POST["confirm_password"])) {
    $confirm_password_error = "Please confirm password";
  } else {
    $confirm_password = test_input($_POST["confirm_password"]);
    // Check if passwords match
    if ($confirm_password != $password) {
      $confirm_password_error = "Passwords do not match";
    }
  }
  
  // If there are no validation errors, compare password with confirm password and convert password to hash before inserting into database.
  if (empty($name_error) && empty($reg_no_error) && empty($password_error) && empty($confirm_password_error)) {
    // Check if passwords match
    if ($confirm_password == $password) {
      $conn = mysqli_connect('localhost', 'root', '', 'student_portal');

      // Check connection
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }

      //adding if reg_no already exists
        $sql = "SELECT * FROM students WHERE reg_no = '$reg_no'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists";
            return false;
        }
      // Convert password to hash
      $password = password_hash($password, PASSWORD_DEFAULT);
      // Insert user data into database
      //generate unique student id should be 7 characters long
      $student_id = uniqid();
      $sql = "INSERT INTO students (student_id, name, reg_no, password) VALUES ('$student_id', '$name', '$reg_no', '$password')";
      if (mysqli_query($conn, $sql)) {
        //head to login page with success message
        header("Location: login.php?success=1");
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    }
    
  }
}
 
// Helper function to sanitize input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

 
<!-- HTML form -->
<!DOCTYPE html>
<html>
<head>
	<title>Student Registration</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        input {
            font-size: 16px;
            color: #333;
        }
        .error {
        	color: red;
        }
    </style>
</head>
<body>
	<div class="container">
		<h1 class="mt-5 mb-4 text-center">Student Registration</h1>
		<p class="text-center">Please fill in this form to create an account.</p>
		
		<div class="row justify-content-between">
			<div class="col-6">
				<!-- HTML form with Bootstrap styling -->
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>" autocomplete="off">
						<span class="error"><?php echo $name_error;?></span>
					</div>
					<div class="form-group">
						<label for="reg_no">Email:</label>
						<input type="text" class="form-control" id="reg_no" name="reg_no" value="<?php echo $reg_no;?>" autocomplete="off">
						<span class="error"><?php echo $reg_no_error;?></span>
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" class="form-control" id="password" name="password" value="<?php echo $password;?>" autocomplete="off">
						<span class="error"><?php echo $password_error;?></span>
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirm Password:</label>
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password;?>">
						<span class="error"><?php echo $confirm_password_error;?></span>
					</div>
					<input type="submit" class="btn btn-primary" name="submit" value="Register">
				</form>
				<p class="mt-4 text-center">Already have an account? <a href="login.php">Login</a></p>
			</div>
      <div class="col-6">
        <img src="./register.jpg" class="img-fluid" alt="">
      </div>
		</div>
	</div>
	
	<!-- Bootstrap JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

