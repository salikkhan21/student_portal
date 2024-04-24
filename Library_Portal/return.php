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
        // check if the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // retrieve the form data
            $book_id = $_POST['book_id'];
            $std_id = $_GET['std_id'];
            
            // validate the form data
            if (empty($book_id)) {
                echo '<p style="color: red;">Please enter book id.</p>';
            } else {
                // connect to the database
                $conn = mysqli_connect('localhost', 'root', '', 'library_portal');
                
                if (!$conn) {
                    die('Could not connect to the database: ' . mysqli_connect_error());
                }
                
                // escape special characters in the form data to prevent SQL injection
                $book_id = mysqli_real_escape_string($conn, $book_id);
                $std_id = mysqli_real_escape_string($conn, $std_id);
                
                // check if the book is borrowed by the student
                $sql = "SELECT * FROM my_account WHERE book_id = '$book_id' AND student_id = '$std_id'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 0) {
                    echo '<p style="color: red;">The book is not borrowed by you.</p>';
                } else {
                    // update the book copy and update my_account table if the book is returned after the due date add fine to the student 15$ per day
                    $sql = "UPDATE books SET copies = copies + 1 WHERE book_id = '$book_id'";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        //update date_returned to now and overdue to 15$ per day if the book is returned after the due date, due_date is 7 days from the date_borrowed
                        $overdue = "SELECT (DATEDIFF(NOW(), due_date) * 15) FROM my_account WHERE book_id = '$book_id' AND student_id = '$std_id'";
                        $sql = "UPDATE my_account SET date_returned = NOW() WHERE book_id = '$book_id' AND student_id = '$std_id'";
                        $result = mysqli_query($conn, $sql);
                        $overdue = $conn->query($overdue);
                        $overdue = $overdue->fetch_assoc();
                        $overdue = $overdue['(DATEDIFF(NOW(), due_date) * 15)'];
                        
                        if ($overdue > 0) {
                            $invoice_ref = uniqid();
                            $sql = "INSERT INTO invoices (invoice_ref, student_id, amount_due) VALUES ('$invoice_ref', '$std_id', '$overdue')";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                $overdue = $overdue . "$";
                                //header to my_account page
                                header('Location: my_account.php?return=1&invoice_ref='.$invoice_ref.'&overdue='.$overdue.'&std_id=' . $_GET['std_id']);
                            } else {
                                echo '<p style="color: red;">The book is not returned successfully.</p>';
                            }
                        } else {
                            header('Location: my_account.php?return=2&std_id=' . $_GET['std_id']);
                        }
                    } else {
                        echo '<p style="color: red;">The book is not returned successfully.</p>';
                    }
                    
                }
                
                // close the database connection
                mysqli_close($conn);
            }
        }
    ?>

    <h1>Book Return</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']). '?std_id=' . $_GET['std_id']; ?>" method="post">
        <div class="form-group">
            <label for="book_id">Book ID (ISBN):</label>
            <input type="text" class="form-control" id="book_id" name="book_id" placeholder="Enter Book ISBN">
        </div>
        
        <button type="submit" class="btn btn-primary">Return</button>

    </div>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
