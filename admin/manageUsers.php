<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // User is not logged in as admin, redirect to login page
    header("Location: ../employee.php");
    exit();
}

// Include database connection
include '../connection.php';

// Update user and associated projects
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if(isset($_POST['passcode']) && isset($_POST['fullName']) && isset($_POST['projects'])){
        foreach ($_POST['passcode'] as $username => $passcode) {
            $fullName = $_POST['fullName'][$username];
            $projects = $_POST['projects'][$username];

            $passcode = $conn->real_escape_string($passcode);
            $fullName = $conn->real_escape_string($fullName);
            $username = $conn->real_escape_string($username);

            $sql_update_user = "UPDATE login SET passcode='$passcode', fullName='$fullName' WHERE username='$username'";
            if ($conn->query($sql_update_user) === TRUE) {
                
            } else {
                echo "Error updating user $username: " . $conn->error . "<br>";
            }

            $sql_delete_projects = "DELETE FROM userProjects WHERE username='$username'";
            if ($conn->query($sql_delete_projects) === FALSE) {
                echo "Error deleting existing projects: " . $conn->error . "<br>";
            }

            foreach($projects as $projectName){
                $projectName = $conn->real_escape_string($projectName);
                $sql_get_fromWebsite = "SELECT fromWebsite FROM projects WHERE projectName='$projectName'";
                $result_get_fromWebsite = $conn->query($sql_get_fromWebsite);
                if ($result_get_fromWebsite->num_rows > 0) {
                    $row = $result_get_fromWebsite->fetch_assoc();
                    $fromWebsite = $conn->real_escape_string($row['fromWebsite']);

                    $sql_insert_project = "INSERT INTO userProjects (username, projectName, fromWebsite) VALUES ('$username', '$projectName', '$fromWebsite')";
                    if ($conn->query($sql_insert_project) === FALSE) {
                        echo "Error inserting project for user $username: " . $conn->error . "<br>";
                    }
                } else {
                    echo "Error: Project '$projectName' not found.<br>";
                }
            }
        }
    }
}

if (isset($_POST['delete'])) {
    $username_to_delete = $conn->real_escape_string($_POST['deleteUsername']);
    $conn->begin_transaction();

    $sql_delete_projects = "DELETE FROM userProjects WHERE username='$username_to_delete'";
    if ($conn->query($sql_delete_projects) === FALSE) {
        echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Error while deleting user projects: ' . $conn->error . '"); });</script>';
        $conn->rollback();
    } else {
        $sql_delete = "DELETE FROM login WHERE username='$username_to_delete'";
        if ($conn->query($sql_delete) === FALSE) {
            echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("Error while deleting user: ' . $conn->error . '"); });</script>';
            $conn->rollback();
        } else {
            $conn->commit();
            echo '<script>document.addEventListener("DOMContentLoaded", function() { showPopup("User and associated projects have been deleted."); });</script>';
        }
    }
}

$sql = "SELECT * FROM login";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../images/Favicon.png">
    <link rel="stylesheet" type="text/css" href="styles/manageUsers-Style.css">
    <style>
        .project-list {
            max-height: 4em;
            overflow-y: auto;
        }
        .project {
            cursor: pointer;
        }
        td.projects-column {
            width: 30%;
        }
        .btn-34 {
            margin-left: 68.5rem;
            bottom: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        <button class="btn-34" onclick="window.location.href='admin.php'"><span>Return</span></button>
        <form id="userForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
            <table>
                <tr>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Full Name</th>
                    <th class="projects-column">Projects</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $username = $row['username'];
                        $userProjects = [];
                        $sql_user_projects = "SELECT projectName FROM userProjects WHERE username='" . $conn->real_escape_string($username) . "'";
                        $result_user_projects = $conn->query($sql_user_projects);
                        if ($result_user_projects->num_rows > 0) {
                            while ($user_project_row = $result_user_projects->fetch_assoc()) {
                                $userProjects[] = $user_project_row['projectName'];
                            }
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><input type="password" name="passcode[<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>]" value="<?php echo htmlspecialchars($row['passcode'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
                            <td><input type="text" name="fullName[<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>]" value="<?php echo htmlspecialchars($row['fullName'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
                            <td class="projects-column">
                                <div class="project-list">
                                    <?php
                                    $sql_projects = "SELECT projectName FROM projects";
                                    $result_projects = $conn->query($sql_projects);
                                    if ($result_projects->num_rows > 0) {
                                        while ($project_row = $result_projects->fetch_assoc()) {
                                            $projectName = $project_row['projectName'];
                                            $isRegistered = in_array($projectName, $userProjects);
                                            $class = $isRegistered ? "project-highlight" : "";
                                            ?>
                                            <div class="project <?php echo $class; ?>" data-username="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" data-project="<?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="checkbox" name="projects[<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>][]" value="<?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?>" <?php if($isRegistered) echo "checked"; ?>>
                                                <?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "No projects found";
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <input type="submit" class="button update-button" name="update" value="Update">
                            </td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
                                    <input type="hidden" name="deleteUsername" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="submit" name="delete" value="Delete" class="button remove-button" onclick="return confirm('Are you sure you want to delete this user?')">
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found.</td></tr>";
                }
                ?>
            </table>
        </form>
    </div>
    <script src="javascript/addUser.js" charset="UTF-8"></script>
    <script>
        document.querySelectorAll('.project').forEach(function(project) {
            project.addEventListener('click', function() {
                this.classList.toggle('project-highlight');
                var checkbox = this.querySelector('input[type="checkbox"]');
                if(checkbox){
                    checkbox.checked = !checkbox.checked;
                }
            });
        });
    </script>
</body>
</html>
