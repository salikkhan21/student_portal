<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Portal</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 90px;
        }
        h1 {
            color: #1a1a1a;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Library Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                
            </ul>
            <ul class="navbar-nav ml-auto">
                
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
    <?php
        // check if the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // retrieve the form data
            $std_id = $_POST['std_id'];
            $password = $_POST['password'];
            
            // validate the form data
            if (empty($std_id)) {
                echo '<p style="color: red;">Please enter the student ID.</p>';
            } else {
                // connect to the database
                $conn = mysqli_connect('localhost', 'root', '', 'library_portal');
                
                if (!$conn) {
                    die('Could not connect to the database: ' . mysqli_connect_error());
                }
                
                // escape special characters in the form data to prevent SQL injection
                $std_id = mysqli_real_escape_string($conn, $std_id);
                
                // query the database for the user with the given registration number
                $query = "SELECT * FROM library_login WHERE student_id='$std_id'";
                $result = mysqli_query($conn, $query);
                
                if (!$result) {
                    die('Could not query the database: ' . mysqli_error($conn));
                }
                
                // check if the user exists
                if (mysqli_num_rows($result) == 1) {
                    // check if the password matches
                    $row = mysqli_fetch_assoc($result);
                    //verify the password
                    if (password_verify($password, $row['password'])) {
                        header("Location: library_dashboard.php?std_id=$std_id");
                        exit;
                    } else {
                        echo '<p style="color: red;">Invalid password.</p>';
                    }
                } else {
                    //check if student id exists in student table
                    $conn2 = mysqli_connect('localhost', 'root', '', 'student_portal');
                    $query = "SELECT * FROM students WHERE student_id='$std_id'";
                    $result = mysqli_query($conn2, $query);
                    if (!$result) {
                        die('Could not query the database: ' . mysqli_error($conn));
                    }
                    if (mysqli_num_rows($result) == 0) {
                        echo '<p style="color: red;">Invalid student ID.</p>';
                    } else{
                    // save that student id in library_login table
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO library_login (student_id, password) VALUES ('$std_id', '$password')";
                    $result = mysqli_query($conn, $query);
                    if (!$result) {
                        die('Could not query the database: ' . mysqli_error($conn));
                    }
                    header("Location: library_dashboard.php?std_id=$std_id");
                }
                }
            }
        }
    ?>
    <h2>Login</h2>
    <p style="color: green;">If you are a new user, please enter your valid student ID and password to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="invoice_ref">Student ID:</label>
                <input type="text" class="form-control" id="std_id" name="std_id" placeholder="Enter Student ID" required>
            </div>
            <div class="form-group">
                <label for="invoice_ref">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
