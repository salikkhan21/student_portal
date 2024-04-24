<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
            //center the form
            
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
        fieldset {
        	width: 50%;
        	margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="container">
        <div class="row">
            <div class="col-6">
            <fieldset class="mt-5">
    <h1 class="mt-5">Student Login</h1>
    <?php
    if(isset($_GET['success'])) {
            echo "<p style='color: green;'>You have successfully registered.</p>";
        }
        ?>
    <p>Please enter your email and password to log in:</p>
    <?php
        // check if the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // retrieve the form data
            $reg_no = $_POST['reg_no'];
            $password = $_POST['password'];
            
            // validate the form data
            if (empty($reg_no) || empty($password)) {
                echo '<p style="color: red;">Please enter both email and password.</p>';
            } else {
                // connect to the database
                $conn = mysqli_connect('localhost', 'root', '', 'student_portal');
                
                if (!$conn) {
                    die('Could not connect to the database: ' . mysqli_connect_error());
                }
                
                // escape special characters in the form data to prevent SQL injection
                $reg_no = mysqli_real_escape_string($conn, $reg_no);
                $password = mysqli_real_escape_string($conn, $password);
                
                // query the database for the user with the given email
                $query = "SELECT * FROM students WHERE reg_no='$reg_no'";
                $result = mysqli_query($conn, $query);
                
                if (!$result) {
                    die('Could not query the database: ' . mysqli_error($conn));
                }
                
                // check if the user exists
                if (mysqli_num_rows($result) == 1) {
                    // the user exists, fetch the user's data
                    $row = mysqli_fetch_assoc($result);
                    
                    // compare the input password with the hashed password in the database
                    if (password_verify($password, $row['password'])) {
                        // the password is correct, redirect to the view_courses.php page with the user's id
                        header("Location: dashboard.php?std_id=$row[student_id]");
                        exit;
                    } else {
                        // the password is incorrect, show an error message
                        echo '<p style="color: red;">Invalid password.</p>';
                    }
                } else {
                    // the user does not exist, show an error message
                    echo '<p style="color: red;">Invalid Email.</p>';
                }
                
                // close the database connection
                mysqli_close($conn);
            }
        }
    ?>
   
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="mb-3">
            <label for="reg_no" class="form-label">Email:</label>
            <input type="text" class="form-control" name="reg_no" id="reg_no" autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" id="password" autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <br>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</fieldset>
            </div>
            <div class="col-6">
                <img src="./log.jpg" alt="" class="img-fluid">
            </div>
        </div>
    </div>
<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
