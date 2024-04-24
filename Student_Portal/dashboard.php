<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-xmFkyg5vYB9erFipkaXcPMLzLHevFgLIz4IEYzY4+iM3JqE4nPFFX9V03bXaGkIv" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
    href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
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
        td:nth-child(5) {
            text-align: center;
        }
        td:nth-child(5) a {
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
        td:nth-child(5) a:hover {
            background-color: #3e8e41;
        }
        p {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-black bg-black fixed-top">
        <a class="navbar-brand" href="dashboard.php?std_id=<?php echo $_GET['std_id']; ?>">Student Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="student_profile.php?student_id=<?php echo $_GET['std_id']; ?>">View/Update Profile</a>
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
        <p>Welcome to the dashboard.</p>
        <h1>Courses / Enrollments</h1>
    <?php
        if(isset($_GET['success'])) {
            echo "<p style='color: green;'>You have successfully enrolled in the course. Please visit Payment Portal to pay invoice reference number: " . $_GET['invoice_ref'] . "</p>";
        }
        // Database connection
        $conn = mysqli_connect('localhost', 'root', '', 'student_portal');

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Get student ID from URL parameter
        $student_id = $_GET['std_id'];

        $sql = "SELECT * FROM courses";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
    ?>
    <table id="datatable">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Course Fee</th>
                <th>Enrollment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            
                // Output each course in a table
    
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["course_id"] . "</td>";
                    echo "<td>" . $row["course_name"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["fee"] . "</td>";
                    //loop through enrolments table where student_id = student_id and course_id = course_id
                    $sql2 = "SELECT * FROM enrolments WHERE course_id = " . $row["course_id"] . " AND student_id = '$_GET[std_id]'"; 
                    $result2 = mysqli_query($conn, $sql2);
                    if ($result2 && mysqli_num_rows($result2) > 0) {
                        echo "<td>Enrolled</td>";
                    } else {
                        echo "<td>Not Enrolled</td>";
                    }
                    echo "<td><a href='view_course.php?course_id=" . $row["course_id"] . "&std_id=" . $student_id . "' class='btn btn-primary'><i class='fa fa-eye'></i> View</a></td>";
                    echo "</tr>";
                }
    
                echo "</table>";
            } else {
                echo "No courses found.";
            }
    
            // Close database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-
pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
</body>
</html>
