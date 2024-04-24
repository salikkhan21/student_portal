<?php
// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'student_portal');

// Check connection
if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Update the student's profile information in the database
$student_id = $_POST['student_id'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$password = $_POST['password'];
if($password == "" || $password == null) {
    // Return false if the password is empty
    // also display messsage on student_profile.php that the password cannot be empty
    header("Location: student_profile.php?student_id=$student_id&error=1");
    exit();
}
$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE students SET name='$name', surname='$surname', password='$password' WHERE student_id='$student_id'";

if (mysqli_query($conn, $sql)) {
    echo "Profile updated successfully";
} else {
    echo "Error updating profile: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
