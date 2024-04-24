
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
		h1 {
            text-align: center;
            margin-top: 50px;
        }

        hr {
            margin-bottom: 50px;
        }

        .form-group {
            margin-top: 20px;
        }

        .btn-primary {
            margin-top: 20px;
        }
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
<?php
// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'student_portal');

// Check connection
if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the student's profile information in the database
    $student_id = $_GET['student_id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
	if($name == "" || $name == null || $surname == "" || $surname == null) {
		// Return false if the name or surname is empty
		// also display messsage on student_profile.php that the name and surname cannot be empty
		header("Location: student_profile.php?student_id=$student_id&error=2");
		exit();
	}
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
        header("Location: student_profile.php?student_id=$student_id&success=1");

    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Retrieve the student's profile information from the database
$student_id = $_GET['student_id'];
$sql = "SELECT * FROM students WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);

// Check if the query returned any rows
if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
		$student_id = $row['student_id'];
        $name = $row['name'];
        $password = $row['password'];
        $surname = $row['surname'];
    }
} else {
    echo 'No records found';
}

// Close the database connection
mysqli_close($conn);
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="dashboard.php?std_id=<?php echo $_GET['student_id']; ?>">Student Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="student_profile.php?student_id=<?php echo $_GET['student_id']; ?>">View/Update Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?std_id=<?php echo $_GET['student_id']; ?>">Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="graduation.php?std_id=<?php echo $_GET['student_id']; ?>">Graduation</a>
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
	<h1>View/Update Student Profile</h1>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<h2>View Profile</h2>
				<?php
					if(isset($_GET['error']) && $_GET['error'] == 1) {
						echo '<div class="alert alert-danger" role="alert">Password cannot be empty</div>';
					}
					if(isset($_GET['error']) && $_GET['error'] == 2) {
						echo '<div class="alert alert-danger" role="alert">Name and Surname cannot be empty</div>';
					}
					if(isset($_GET['success']) && $_GET['success'] == 1) {
						echo '<div class="alert alert-success" role="alert">Profile updated successfully</div>';
					}
				?>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?student_id=$student_id"); ?>">
					<div class="form-group">
						<label for="id">Student ID:</label>
						<input type="text" class="form-control" id="id" name="id" value="<?php echo $student_id; ?>" readonly>
					</div>
					<div class="form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" readonly>
					</div>
                    <div class="form-group">
						<label for="surname">SurName:</label>
						<input type="text" class="form-control" id="surname" name="surname" value="<?php echo $surname; ?>" readonly>
					</div>
					<div class="form-group" id="password-fields" style="display: none;">
						<label for="password">Password:</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
					</div>
					<button type="button" class="btn btn-primary" id="edit-profile-btn">Edit</button>
					<button type="submit" class="btn btn-primary" id="save-profile-btn" style="display: none;">Save</button>
				</form>
			</div>
		</div>
	</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@2.9.3/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
		$(document).ready(function() {
			$('#edit-profile-btn').click(function() {
				$('#name').prop('readonly', false);
                $('#surname').prop('readonly', false);
				$('#password-fields').show();
				$('#edit-profile-btn').hide();
				$('#save-profile-btn').show();
			});

		});
	</script>
</body>
</html>

