<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Portal</title>
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
        <a class="navbar-brand" href="#">Payment Portal</a>
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
            $invoice_ref = $_POST['invoice_ref'];
            
            // validate the form data
            if (empty($invoice_ref)) {
                echo '<p style="color: red;">Please enter the invoice reference number.</p>';
            } else {
                // connect to the database
                $conn = mysqli_connect('localhost', 'root', '', 'finance_portal');
                
                if (!$conn) {
                    die('Could not connect to the database: ' . mysqli_connect_error());
                }
                
                // escape special characters in the form data to prevent SQL injection
                $invoice_ref = mysqli_real_escape_string($conn, $invoice_ref);
                
                // query the database for the user with the given registration number
                $query = "SELECT * FROM invoices WHERE invoice_ref='$invoice_ref'";
                $result = mysqli_query($conn, $query);
                
                if (!$result) {
                    die('Could not query the database: ' . mysqli_error($conn));
                }
                
                // check if the user exists
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    header("Location: invoice.php?invoice_ref=$invoice_ref");
                    exit;
                    
                } else {
                    echo '<p style="color: red;">Invalid reference number.</p>';
                }
                
                // close the database connection
                mysqli_close($conn);
            }
        }
        if (isset($_GET['success'])) {
            echo '<p style="color: green;">Payment successful.</p>';
        }
    ?>
    <h2>Find Invoice</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="invoice_ref">Invoice Reference:</label>
                <input type="text" class="form-control" id="invoice_ref" name="invoice_ref" placeholder="Enter your unique reference number" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Find Invoice</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
