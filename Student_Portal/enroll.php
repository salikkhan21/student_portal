<?php

$course_id = $_GET['course_id'];
$student_id = $_GET['std_id'];
$fee = $_GET['fee'];

// connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'student_portal');
$conn2 = mysqli_connect('localhost', 'root', '', 'finance_portal');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to insert a new record into the enrolments table
$sql = "INSERT INTO enrolments (course_id, student_id)
        VALUES ($course_id, '$student_id')";

// Execute the query
if (mysqli_query($conn, $sql)) {
    //generate invoice_ref unique containing alphabets and numbers
    $invoice_ref = uniqid();
    // Insert data into the invoices table
    $sql2 = "INSERT INTO invoices (invoice_ref, student_id, amount_due) VALUES ('$invoice_ref', '$student_id', $fee)";
    if (mysqli_query($conn2, $sql2)) {
        // Redirect to the courses page with success message
        header("Location: dashboard.php?std_id=$student_id&success=1&invoice_ref=$invoice_ref");
    } else {
        echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
    }
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
