<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // User is not logged in as admin, redirect to login page
    header("Location: ../employee.php");
    exit();
}

include '../connection.php';

// Set the charset to utf8mb4
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addUser'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $passcode = $conn->real_escape_string($_POST['passcode']);
    $role = $conn->real_escape_string($_POST['role']);
    $fullName = isset($_POST['fullName']) ? $conn->real_escape_string($_POST['fullName']) : '';

    // Insert user details into login table
    $sql = "INSERT INTO login (username, passcode, role, fullName) VALUES ('$username', '$passcode', '$role', '$fullName')";
    if ($conn->query($sql) === TRUE) {
        // Get the selected projects from the form
        $projects = isset($_POST['projects']) ? $_POST['projects'] : [];

        // Insert each project for the user into userProjects table
        foreach ($projects as $project) {
            $projectData = json_decode($project, true);
            $projectName = $conn->real_escape_string($projectData['projectName']);
            $fromWebsite = $conn->real_escape_string($projectData['fromWebsite']);

            $sql = "INSERT INTO userProjects (username, projectName, fromWebsite) VALUES ('$username', '$projectName', '$fromWebsite')";
            $conn->query($sql);
        }

        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("User and projects have been added."); });</script>';
    } else {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Could not add user. Try again."); });</script>';
    }
}

// Fetch roles from login table
$sql = "SELECT DISTINCT role FROM login";
$result = $conn->query($sql);

$roles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8');
    }
}

// Fetch projects from another table (e.g., 'projects')
$sql = "SELECT projectName, fromWebsite FROM projects";
$result = $conn->query($sql);

$projects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = [
            'projectName' => htmlspecialchars($row['projectName'], ENT_QUOTES, 'UTF-8'),
            'fromWebsite' => htmlspecialchars($row['fromWebsite'], ENT_QUOTES, 'UTF-8')
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../images/Favicon.png">
    <link rel="stylesheet" type="text/css" href="styles/admin-style.css">
    <style>
        .selected {
            background-color: #86ed94;
        }
        .project-container {
            max-height: 200px; 
            overflow-y: auto;
        }
        .container {
            width: 800px;
        }
        #submitButton {
            width: 25%;
            padding: 10px;
            background-color: #2979AF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 0 auto; 
        }
        #submitButton:hover {
            background-color: #3498db;
        }
        .btn-34 {
            margin-left: 41rem;
            bottom: 2.5rem;
        }
		
		
    </style>
</head>
<body>
    <div class="container">
        <h1>Add User</h1>
        <button class="btn-34" onclick="window.location.href='admin.php'"><span>Return</span></button>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="fullName">Full Name:</label><br>
            <input type="text" id="fullName" name="fullName" required><br>
			

            <label for="passcode">Password:</label><br>
            <input type="password" id="passcode" name="passcode" required><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="">Choose Role</option>
                <?php foreach ($roles as $role) : ?>
                    <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="username">Email:</label><br>
            <input type="email" id="username" name="username"><br>

            <label style="font-size: larger;">Projects:</label><label style="font-size: smaller;"> (Click to select)</label><br>
            <div class="project-container">
                <?php foreach ($projects as $project) : ?>
                    <div class="project" style="cursor: pointer;">
                        <input type="checkbox" name="projects[]" value="<?php echo htmlspecialchars(json_encode($project), ENT_QUOTES, 'UTF-8'); ?>" style="display: none;">
                        <?php echo $project['projectName']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <br>

            <input type="submit" id="submitButton" name="addUser" value="Add User">
        </form>
    </div>

    <script src="javascript/addUser.js" charset="UTF-8"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projects = document.querySelectorAll('.project');
            projects.forEach(function(project) {
                project.addEventListener('click', function() {
                    project.classList.toggle('selected');
                    const checkbox = project.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                });
            });
        });
    </script>
</body>
</html>
