<?php
include '../connection.php';
$data_html = htmlentities($data, ENT_QUOTES, 'UTF-8');

// Fetch distinct full names from login table
$sql = "SELECT DISTINCT fullName FROM login";
$result = $conn->query($sql);

$owners = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $owners[] = $row['fullName'];
    }
}

// Update project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $projectName = mysqli_real_escape_string($conn, $_POST['projectName']);
    $projectOwner = mysqli_real_escape_string($conn, $_POST['projectOwner']);
    $projectOrg = mysqli_real_escape_string($conn, $_POST['projectOrg']);
    $projectDesc = mysqli_real_escape_string($conn, $_POST['projectDesc']);
    $fromWebsite = mysqli_real_escape_string($conn, $_POST['fromWebsite']);

    $sql = "UPDATE projects SET projectOwner='$projectOwner', projectOrg='$projectOrg', projectDesc='$projectDesc', fromWebsite='$fromWebsite' WHERE projectName='$projectName'";

    if ($conn->query($sql) === TRUE) {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("The project has been updated."); });</script>';
    } else {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Error while updating project: ' . $conn->error . '"); });</script>';
    }
}

// Handle delete requests
if (isset($_GET['delete'])) {
    $projectName_to_delete = $_GET['delete'];
    
    // First, delete associated rows from userProjects table
    $sql_delete_user_projects = "DELETE FROM userProjects WHERE projectName='$projectName_to_delete'";
    if ($conn->query($sql_delete_user_projects) === TRUE) {
        // Proceed to delete the project
        $sql_delete = "DELETE FROM projects WHERE projectName='$projectName_to_delete'";
        if ($conn->query($sql_delete) === TRUE) {
            echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("The project and associated data have been deleted."); });</script>';
        } else {
            echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Error while deleting project: ' . $conn->error . '"); });</script>';
        }
    } else {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Error while deleting associated data from userProjects table: ' . $conn->error . '"); });</script>';
    }
}

// Get projects from the database
$sql = "SELECT * FROM projects";
$result = $conn->query($sql);

// Function to shorten URL
function shortenUrl($url, $length = 45) {
    if (strlen($url) > $length) {
        return substr($url, 0, $length) . '...';
    }
    return $url;
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
	<link rel="icon" type="image/png" sizes="32x32" href="../images/Favicon.png">
    <link rel="stylesheet" type="text/css" href="styles/admin-style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 50px auto; /* Center the main container vertically and horizontally */
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            transition: box-shadow 0.3s ease;
        }
		
		.btn-34 {
		    margin-left: 68rem;
		    bottom: 3.5rem;
	    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Projects</h1>
        <button class="btn-34" onclick="window.location.href='admin.php'"><span>Return</span></button>
    
        <table>
            <tr>
                <th>Project Name</th>
                <th>Project Owner</th>
                <th>Project Organisation</th>
                <th>Project Description</th>
                <th>URL</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <td><?php echo $row['projectName']; ?></td>
                            <td> 
                                <select id="projectOwner" name="projectOwner" required>
                                    <option value="">Choose Owner</option>
                                    <?php foreach ($owners as $owner) : ?>
                                        <option value="<?php echo $owner; ?>" <?php if ($owner == $row['projectOwner']) echo 'selected'; ?>><?php echo $owner; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="text" name="projectOrg" value="<?php echo $row['projectOrg']; ?>" style="width: 150px;"></td>
                            <td><input type="text" name="projectDesc" value="<?php echo $row['projectDesc']; ?>" style="width: 200px;"></td>
                            <td>
                                <a href="<?php echo $row['fromWebsite']; ?>" target="_blank">
                                    <?php echo shortenUrl($row['fromWebsite']); ?>
                                </a>
                            </td>
                            <td>
                                <input type="hidden" name="projectName" value="<?php echo $row['projectName']; ?>">
                                <input type="submit" name="update" value="Update">
                            </td>
                        </form>
                        <td>
                            <a href="?delete=<?php echo $row['projectName']; ?>" onclick="return confirm('Are you sure you want to delete this project?');" class="button delete-button">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>No projects found.</td></tr>";
            }
            ?>
        </table>
    </div>
    <script src="javascript/addUser.js" charset="UTF-8"></script>
</body>
</html>
