<?php
include '../../../connection.php';
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect or handle unauthenticated user
    exit("You are not logged in.");
}

// Get username from session
$username = $_SESSION['username'];

// Check if the request is an AJAX request to mark feedback as read
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_id'])) {
    $feedbackId = $_POST['feedback_id'];

    // Update feedback status as read for the specific feedback item
    $sql = "UPDATE feedbackInfo AS f 
            JOIN userProjects AS up ON f.fromWebsite = up.fromWebsite 
            SET f.feedbackStatus = 1 
            WHERE f.ID = ? AND f.feedbackStatus = 0 AND up.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $feedbackId, $username); // "i" indicates integer type, "s" indicates string type
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
    exit();
}

// SQL query to fetch unread feedback for the current user, sorted by the newest feedback first
$sql = "SELECT * FROM feedbackInfo AS f 
        JOIN userProjects AS up ON f.fromWebsite LIKE CONCAT(up.fromWebsite, '%') 
        WHERE f.feedbackStatus = 0 AND up.username = '$username'
        ORDER BY f.time DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
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
        .read-more {
          float: left;
        }
        .container {
          padding: 20px;
        }
        table {
          width: 100%;
          border-collapse: collapse;
        }
        th, td {
          padding: 10px;
          text-align: left;
          border-bottom: 1px solid #ddd;
        }
        th {
          background-color: #2C3240;
          color: white;
        }
        .no-feedback {
          text-align: left;
          font-size: 18px;
          margin-left: 10px; /* Adjust this value to match the header alignment */
        }
    </style>
</head>
<body>

<div class='container'>
<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Topic</th><th>Feedback</th><th>Action</th><th>Read More</th></tr>"; // Updated header

    // Output each feedback
    while ($row = $result->fetch_assoc()) {
        $topic = substr($row["topic"], 0, 50); 
        $feedback = substr($row["feedback"], 0, 100); 

        // Start a new table row for each feedback
        echo "<tr>";
        echo "<td>$topic</td>";
        echo "<td>$feedback</td>";
        
        // Buttons for actions (Mark As Read and Read More)
        echo "<td>";
        // Button for marking individual feedback as read
        echo "<button class='btn-1 mark-read' data-feedback-id='{$row["ID"]}'>Mark As Read</button>";
        echo "</td>";
        echo "<td>";
        echo "<button class='read-more btn-1' data-feedback-id='{$row["ID"]}'>Read More</button>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='no-feedback'>No unread feedback</div>";
}
?>
</div> <!-- Close container -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.mark-read').click(function() {
        var feedbackId = $(this).data('feedback-id');
        $.ajax({
            type: 'POST',
            url: 'outputNewData.php',
            data: { feedback_id: feedbackId },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status === 'success') {
                    location.reload(); // Reload the page to update feedback list
                } else {
                    alert('Error marking feedback as read: ' + res.message);
                }
            }
        });
    });
});
</script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
