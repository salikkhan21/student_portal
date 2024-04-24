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
                <li class="nav-item">
                    <a class="nav-link" href="search_invoice.php">Find Another Invoice</a>
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
        //Get invoice data
        $invoice_ref = $_GET['invoice_ref'];

        $conn = mysqli_connect('localhost', 'root', '', 'finance_portal');
                
        if (!$conn) {
            die('Could not connect to the database: ' . mysqli_connect_error());
        }

        //if pay button is clicked
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //delete invoice from database
            $invoice_ref = $_POST['invoice_ref'];
            // set status to PAID
            $query = "UPDATE invoices SET status='PAID' WHERE invoice_ref='$invoice_ref'";

            $result = mysqli_query($conn, $query);
            if (!$result) {
                die('Could not query the database: ' . mysqli_error($conn));
            }
            //redirect to search invoice page with success message
            header('Location: invoice.php?invoice_ref=' . $invoice_ref . '');
            exit;
        }
        
        // escape special characters in the form data to prevent SQL injection
        $invoice_ref = mysqli_real_escape_string($conn, $invoice_ref);

        
        // query the database for the user with the given registration number
        $query = "SELECT * FROM invoices WHERE invoice_ref='$invoice_ref'";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            die('Could not query the database: ' . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);
    ?>
    <h2>Invoice:</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label><b>Reference:</b> <?php echo $row['invoice_ref']; ?></label><br>
                <input type="hidden" name="invoice_ref" value="<?php echo $row['invoice_ref']; ?>">
                <label><b>Student ID:</b> <?php echo $row['student_id']; ?></label><br>
                <label><b>Amount:</b> <?php echo $row['amount_due']; ?></label><br>
                <label><b>Due Date:</b> <?php echo date('Y-m-d', strtotime('+2 days')); ?></label><br>
                <label><b>Status:</b> <?php echo $row['status']; ?></label>
            </div>
            <!-- If $row['status'] == 'PAID' then dont show this button show payment sucessful green message -->
            <?php if ($row['status'] == 'PAID') { ?>
                <div class="alert alert-success" role="alert">
                    Payment Successful!
                </div>
            <?php } else { ?>
                <button type="submit" class="btn btn-primary">Pay Invoice</button>
            <?php } ?>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
