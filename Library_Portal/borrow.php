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
                
                // check if the book is exists in the database
                $sql = "SELECT * FROM books WHERE book_id = '$book_id'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 1) {
                    // check if the book is already borrowed by the student
                    $sql = "SELECT * FROM borrow WHERE book_id = '$book_id' AND std_id = '$std_id'";
                    $result = mysqli_query($conn, $sql);
                    
                    if (!empty($result) && mysqli_num_rows($result) == 1) {
                        echo '<p style="color: red;">You have already borrowed this book.</p>';
                    } else {
                        // check if the book copies are available
                        $sql = "SELECT * FROM books WHERE book_id = '$book_id' AND copies = 0";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) == 1) {
                            echo '<p style="color: red;">Book copies are not available.</p>';
                        } else {
                            // insert the borrow data into the my_account database table , due_date is 7 days after now.
                            $sql = "INSERT INTO my_account (student_id, book_id, date_borrowed, due_date) VALUES ('$std_id', '$book_id', NOW(), DATE_ADD(NOW(), INTERVAL 2 DAY))";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                // update the book copies in the database
                                $sql = "UPDATE books SET copies = copies - 1 WHERE book_id = '$book_id'";
                                $result = mysqli_query($conn, $sql);

                                if ($result) {
                                    header('Location: my_account.php?success=1&book_id='.$book_id.'&std_id=' . $_GET['std_id']);
                                } else {
                                    echo '<p style="color: red;">Book borrowed failed.</p>';
                                }
                            } else {
                                echo '<p style="color: red;">Book borrowed failed.</p>';
                            }
                        }
                    }
                } else {
                    echo '<p style="color: red;">Book not found.</p>';
                }
                
                // close the database connection
                mysqli_close($conn);
            }
        }
    ?>

    <h1>Borrow Book</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?std_id=' . $_GET['std_id']; ?>" method="post">
        <div class="form-group">
            <label for="book_id">Book ID (ISBN):</label>
            <input type="text" class="form-control" id="book_id" name="book_id" placeholder="Enter Book ISBN">
        </div>

        <button type="submit" class="btn btn-primary">Borrow</button>

    </div>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
