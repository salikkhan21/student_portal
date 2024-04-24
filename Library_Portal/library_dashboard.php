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
        .custom{
            content:"";
            background-image:url('./lib.jpg') no-repeat center center/cover;
            width:100%;
            height:100vh;
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
    <div class="container custome">

    <h1>All Books</h1>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Copies</th>
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

            // Query the books table
            $sql = "SELECT * FROM books";
            $result = mysqli_query($conn, $sql);

            // Display the data in the table
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["book_id"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["author"] . "</td>";
                    echo "<td>" . $row["year"] . "</td>";
                    echo "<td>" . $row["copies"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No books found.</td></tr>";
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
