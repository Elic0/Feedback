<?php
session_start();

include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    
    // Check which type of update is being performed
    if (isset($_POST["status"])) {
        $status = intval($_POST["status"]);
        if ($id > 0) {
            $sql = "UPDATE feedbackInfo SET feedbackStatus = ? WHERE ID = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ii", $status, $id);
                $stmt->execute();
                $stmt->close();
            }
            echo "Status update successful";
        } else {
            echo "Invalid input.";
        }
    } elseif (isset($_POST["assignedUser"])) {
        $assignedUser = $_POST["assignedUser"];
        if ($id > 0 && !empty($assignedUser)) {
            $sql = "UPDATE feedbackInfo SET assignedUser = ? WHERE ID = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $assignedUser, $id);
                $stmt->execute();
                $stmt->close();
            }
            echo "User assignment successful";
        } else {
            echo "Invalid input.";
        }
    }
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT f.ID, f.topic, f.feedback, f.feedbackStatus, f.assignedUser, f.fromWebsite 
        FROM feedbackInfo AS f 
        JOIN userProjects AS up ON f.fromWebsite LIKE CONCAT(up.fromWebsite, '%') 
        WHERE (f.feedbackStatus = 1 OR f.feedbackStatus = 2) AND
	up.username = '$username'
        ORDER BY CASE WHEN f.feedbackStatus = 1 THEN 0 ELSE 1 END, f.time DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback</title>
<style>
.btn-1,
.btn-1 *,
.btn-1 :after,
.btn-1 :before,
.btn-1:after,
.btn-1:before {
  border: 0 solid;
  box-sizing: border-box;
}
.btn-1 {
  -webkit-tap-highlight-color: transparent;
  -webkit-appearance: button;
  background-color: #2C3240;
  background-image: none;
  color: #fff;
  cursor: pointer;
  font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
    Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
    Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
  font-size: 100%;
  font-weight: 650;
  line-height: 1.5;
  margin: 0;
  -webkit-mask-image: -webkit-radial-gradient(#000, #fff);
  padding: 0.1rem 0.7rem;
  border-radius: 99rem;
  border-width: 2px;
  text-transform: uppercase;
}
.btn-1:disabled {
  cursor: default;
}
.btn-1:-moz-focusring {
  outline: auto;
}
.btn-1:hover {
  color: #999;
}

select {
  background-color: #2C3240;
  color: #fff;
  border: 1px solid #fff;
  padding: 0.5rem;
  border-radius: 5px;
  font-size: 14px;
  appearance: none; 
  -webkit-appearance: none;
  -moz-appearance: none;
  background-repeat: no-repeat;
  background-position: calc(100% - 10px) center;
  cursor: pointer; 
}

select:focus {
  outline: none;
  border-color: #00bcd4;
  box-shadow: 0 0 0 2px #00bcd4;
}

</style>
<script>
function updateStatus(feedbackId, newStatus) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send("id=" + feedbackId + "&status=" + newStatus);
}

function updateAssignedUser(feedbackId, newUser) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send("id=" + feedbackId + "&assignedUser=" + newUser);
}
</script>
</head>
<body>

<?php
if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<table>";
    echo "<tr><th>Topic</th><th>Feedback</th><th>Status</th><th>Assigned User</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $topic = htmlspecialchars(substr($row["topic"], 0, 50), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); 
        $feedback = htmlspecialchars(substr($row["feedback"], 0, 50), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $status = $row["feedbackStatus"];
        $assignedUser = $row["assignedUser"];
        $fromWebsite = $row["fromWebsite"];

        // Fetch users for the dropdown specific to the project
        $userSql = "SELECT u.fullName 
                    FROM login u
                    JOIN userProjects up ON u.username = up.username
                    WHERE up.fromWebsite LIKE CONCAT(?, '%')";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("s", $fromWebsite);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $users = [];
        while ($userRow = $userResult->fetch_assoc()) {
            $users[] = $userRow['fullName'];
        }
        $userStmt->close();

        echo "<tr data-feedback-id='{$row["ID"]}'>"; 
        echo "<td>$topic</td>";
        echo "<td>";
        echo "<div class='feedback-content' data-full-feedback='" . htmlspecialchars($row["feedback"], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "'>$feedback";
        echo "<button class='read-more btn-1'>Read More</button>";
        echo "</div>";
        echo "</td>";
        echo "<td>";
        echo "<select onchange='updateStatus({$row["ID"]}, this.value)'>";
        echo "<option value=''>Select Status</option>";
        echo "<option value='0' " . ($status == 0 ? "selected" : "") . ">New</option>";
        echo "<option value='1' " . ($status == 1 ? "selected" : "") . ">In Progress</option>";
        echo "<option value='2' " . ($status == 2 ? "selected" : "") . ">Closed</option>";
        echo "</select>";
        echo "</td>";
        echo "<td>";
        echo "<select onchange='updateAssignedUser({$row["ID"]}, this.value)'>";
        echo "<option value=''>Select User</option>";
        foreach ($users as $user) {
            echo "<option value='$user' " . ($assignedUser == $user ? "selected" : "") . ">$user</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>"; 
} else {
    echo "No results";
}
?>

</body>
</html>

<?php
$conn->close();
?>
