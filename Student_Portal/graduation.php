<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                    <a class="nav-link" href="dashboard.php?std_id=<?php echo $_GET['std_id']; ?>">Courses</a>
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
    $conn = new mysqli('localhost', 'root', '', 'finance_portal');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the student ID from the URL parameter
    $std_id = $_GET['std_id'];

    // Check if the student has any outstanding invoices
    $sql = "SELECT * FROM invoices WHERE student_id = '$std_id' AND amount_due > 0 AND status = 'OUTSTANDING'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // The student has outstanding invoices, so they are not eligible to graduate
        echo "<h1>Graduation</h1><br>";
        echo "<p style='text-align: center;'>You have the following outstanding invoices:</p>";
        while ($row = $result->fetch_assoc()) {
            $invoice_ref = $row['invoice_ref'];
            $amount_due = $row['amount_due'];
            echo "<p style='text-align: center;'><b>Invoice reference</b> = $invoice_ref, <b>Amount due</b> = $amount_due</p>";
        }
        echo "<p style='color:red; text-align: center;'>You are not eligible to graduate because you have outstanding invoices.</p>";
        echo "<p style='text-align: center;'>You need to pay all outstanding invoices before you are eligible graduate.</p>";
        echo "<p style='color:green; text-align: center;'>Please visit Payment Portal to pay your outstanding invoices.</p>";
        echo "<p style='text-align: center;'><a href='dashboard.php?std_id=$std_id'>Return to dashboard</a></p>";
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
