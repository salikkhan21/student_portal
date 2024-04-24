<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course</title>
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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        td:nth-child(4) {
            text-align: center;
        }
        td:nth-child(4) a {
            display: block;
            width: 50%;
            margin: 0 auto;
            padding: 8px 16px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        td:nth-child(4) a:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Student Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="student_profile.php?student_id=<?php echo $_GET['std_id']; ?>">View/Update Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?std_id=<?php echo $_GET['std_id']; ?>">All Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="graduation.php?std_id=<?php echo $_GET['std_id']; ?>">Graduation</a>
                </li>
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
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'student_portal');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the course ID from the URL
    $course_id = $_GET['course_id'];
    $student_id = $_GET['std_id'];

    // Get the course details from the database
    $sql = "SELECT * FROM courses WHERE course_id = '$course_id'";
    $result = $conn->query($sql);

    //Checking if course already enrolled
    $sql2 = "SELECT * FROM enrolments WHERE course_id = '$course_id' AND student_id = '$_GET[std_id]'";
    $result2 = $conn->query($sql2);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h1>$row[course_name]</h1>";
        echo "<center><p style='width:40%;'>$row[description]</p></center>";
        echo "<center><p><strong>Fee:</strong> $row[fee]</p></center>";
        if(!empty($result2) && $result2->num_rows > 0){
            echo "<center><p style='color:green;'>Enrolled</p></center>";
        }else{
            echo "<center><a href='enroll.php?course_id=" . $row["course_id"] . "&std_id=" . $student_id . "&fee=" . $row["fee"] . "' class='btn btn-primary'>Enroll</a></center>";
        }
    } else {
        // The student does not have any outstanding invoices, so they are eligible to graduate
        echo "<h1>Graduation</h1>";
        echo "<p style='color:green; text-align: center;'>Congratulations! You are eligible to graduate.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
