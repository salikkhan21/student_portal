<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
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
        <a class="navbar-brand" href="#">Library Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="library_dashboard.php?std_id=<?php echo $_GET['std_id']; ?>">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="borrow.php?std_id=<?php echo $_GET['std_id']; ?>">Borrow</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="return.php?std_id=<?php echo $_GET['std_id']; ?>">Return</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_account.php?std_id=<?php echo $_GET['std_id']; ?>">My Account</a>
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
            if(isset($_GET['success']) && $_GET['success'] == 1) {
                $conn = mysqli_connect("localhost", "root", "", "library_portal");

            // Check if the connection is successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
                // Display success message "You have borrowed the "Book name search from books using $_GET['book_id']" until "date borrowed + 2 days"
                $sql = "SELECT * FROM books WHERE book_id = '" . $_GET['book_id'] . "'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $book_name = $row['title'];
                echo "<div class='alert alert-success' role='alert'>You have borrowed the " . $book_name . " until " . date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days')) . "</div>";
            }
            if(isset($_GET['return']) && $_GET['return'] == 1) {
                $overdue = $_GET['overdue'];
                $invoice_ref = $_GET['invoice_ref'];
                echo "<div class='alert alert-primary' role='alert'>Thank you for returning the book. You have been charged ' . $overdue . '. Please visit Payment Portal to pay invoice against reference number '. $invoice_ref . '</div>";
            }
            if(isset($_GET['return']) && $_GET['return'] == 2) {
                echo "<div class='alert alert-success' role='alert'>Thank you for returning the book.</div>";
            }
        ?>

    <h1>My Account</h1>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Book</th>
            <th>Date Borrowed</th>
            <th>Date Returned</th>
            <th>Overdue</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // Connect to the database
            $conn = mysqli_connect("localhost", "root", "", "library_portal");

            // Check if the connection is successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // get all from my_account where student_id 
            $sql = "SELECT * FROM my_account WHERE student_id = '" . $_GET['std_id'] . "'";
            $result = mysqli_query($conn, $sql);

            // Display the data in the table
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["book_id"] . "</td>";
                    echo "<td>" . $row["date_borrowed"] . "</td>";
                    echo "<td>"; 
                    echo $row["date_returned"] ? $row["date_returned"] : "None"; 
                    echo "</td>";
                    echo "<td>";
                    //overdue is if todays date is greater than due date then display overdue days
                    if (date('Y-m-d') > $row["due_date"]) {
                        echo date_diff(date_create(date('Y-m-d')), date_create($row["due_date"]))->format("%a days");
                    } else {
                        echo "";
                    }
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No Details found.</td></tr>";
            }

            // Close the database connection
            mysqli_close($conn);
        ?>
    </tbody>
</table>

    
    
    </div>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
