<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // User is not logged in as admin, redirect to login page
    header("Location: ../employee.php");
    exit();
}
?>

<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addProject'])) {
    $projectName = $conn->real_escape_string($_POST['projectName']);
    $projectOwner = $conn->real_escape_string($_POST['projectOwner']);
    $projectOrg = $conn->real_escape_string($_POST['projectOrg']);
    $projectDesc = $conn->real_escape_string($_POST['projectDesc']);
    $fromWebsite = $conn->real_escape_string($_POST['fromWebsite']);

    $sql = "INSERT INTO projects (projectName, projectOwner, projectOrg, projectDesc, fromWebsite) VALUES ('$projectName', '$projectOwner', '$projectOrg', '$projectDesc', '$fromWebsite')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Project has been added."); });</script>';
    } else {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Could not add project. Try again."); });</script>';
    }
}

// Get owners from the database
$sql = "SELECT DISTINCT fullName FROM login";
$result = $conn->query($sql);

$owners = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $owners[] = $row['fullName'];
    }
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../images/Favicon.png">
    <style>
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
        .container {
            width: 800px;
        }
        .btn-34 {
            left: 40rem;
            bottom: 2.5rem;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="styles/admin-style.css">
</head>
<body>
    <div class="container">
        <h1>Add Project</h1>
        <button class="btn-34" onclick="window.location.href='admin.php'"><span>Return</span></button>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="projectName">Project Name:</label><br>
            <input type="text" id="projectName" name="projectName" required><br>

            <label for="projectOwner">Projekt-Owner:</label><br>
            <select id="projectOwner" name="projectOwner" required>
                <option value="">Choose Owner</option>
                <?php foreach ($owners as $owner) : ?>
                    <option value="<?php echo htmlspecialchars($owner); ?>"><?php echo htmlspecialchars($owner); ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="projectOrg">Project Organisation:</label><br>
            <input type="text" id="projectOrg" name="projectOrg"><br>

            <label for="projectDesc">Project Description:</label><br>
            <textarea id="projectDesc" name="projectDesc" rows="4" cols="50"></textarea><br>

            <label for="fromWebsite">URL:</label><br>
            <input type="text" id="fromWebsite" name="fromWebsite"><br>

            <input type="submit" id="submitButton" name="addProject" value="Add Project">
        </form>
    </div>

    <script src="Javascript/addProject.js" charset="UTF-8"></script>
</body>
</html>
