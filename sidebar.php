<?php
session_start();
require 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "User not logged in";
    exit();
}

// Get the logged-in user's username from the session
$username = $_SESSION['username'];

// Fetch the current notification status from the database
$sql = "SELECT notification FROM login WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($notification_status);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/ionicons@5.5.2/dist/css/ionicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
        }

        .move-down {
            font-size: 20px;
            top: 9px;
            text-align: center;
            color: #f1f1f1;
        }

        #switch {
            top: 15%;
            left: 38%;
            width: 75px;
            height: 40px;
            text-align: center;
        }

        .toggle {
            position: absolute;
            border: 2px solid #444249;
            border-radius: 20px;
            transition: border-color .6s ease-out;
            box-sizing: border-box;
        }

        .toggle.toggle-on {
            border-color: rgba(137, 194, 217, .4);
            transition: all .5s .15s ease-out;
        }

        .toggle-button {
            position: absolute;
            top: 4px;
            width: 28px;
            bottom: 4px;
            right: 39px;
            background-color: #444249;
            border-radius: 19px;
            cursor: pointer;
            transition: all .3s .1s, width .1s, top .1s, bottom .1s;
        }

        .toggle-on .toggle-button {
            top: 3px;
            width: 65px;
            bottom: 3px;
            right: 3px;
            border-radius: 23px;
            background-color: #89c2da;
            box-shadow: 0 0 16px #4b7a8d;
            transition: all .2s .1s, right .1s;
        }

        .toggle-text-on {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            line-height: 36px;
            text-align: center;
            font-family: 'Lato', sans-serif;
            font-size: 18px;
            font-weight: normal;
            cursor: pointer;
            user-select: none; /* All browsers */
            color: rgba(0,0,0,0);
        }

        .toggle-on .toggle-text-on {
            color: #3b6a7d;
            transition: color .3s .15s;
        }

        .toggle-text-off {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 6px;
            line-height: 36px;
            text-align: center;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            font-weight: bold;
            user-select: none; /* All browsers */
            cursor: pointer;
            color: #444249;
        }

        .toggle-on .toggle-text-off {
            color: rgba(0,0,0,0);
        }

        .glow-comp {
            position: absolute;
            opacity: 0;
            top: 10px;
            bottom: 10px;
            left: 10px;
            right: 10px;
            border-radius: 6px;
            background-color: rgba(75, 122, 141, .1);
            box-shadow: 0 0 12px rgba(75, 122, 141, .2);
            transition: opacity 4.5s 1s;
        }

        .toggle-on .glow-comp {
            opacity: 1;
            transition: opacity 1s;
        }

        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: -250px;
            background-color: #191919;
            overflow-x: hidden;
            padding-top: 60px;
            transition: transform 0.5s ease;
        }

        .sidenav.open {
            transform: translateX(250px);
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #f1f1f1;
            display: block;
            transition: color 0.3s;
        }

        .sidenav a:hover {
            color: #a61211;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            color: #f1f1f1;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }

        .settings-icon {
            font-size: 25px;
            cursor: pointer;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .settings-icon:hover {
            background-color: #4C6885;
        }
    </style>
</head>
<body>
    <!-- Selection menu, input the switch here -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class='toggle' id='switch'>
            <div class='toggle-text-off'>OFF</div>
            <div class='glow-comp'></div>
            <div class='toggle-button'></div>
            <div class='toggle-text-on'>ON</div>
        </div>
        <h6 class="move-down"><b>Notifications?</b></h6>
    </div>

    <!-- Used to open the sidemenu -->
    <span class="settings-icon" onclick="toggleNav()">
        <ion-icon name="settings-outline"></ion-icon>
    </span>

    <script>
        function toggleNav() {
            var sidenav = document.getElementById("mySidenav");
            if (sidenav.classList.contains("open")) {
                sidenav.classList.remove("open");
            } else {
                sidenav.classList.add("open");
            }
        }

        function closeNav() {
            document.getElementById("mySidenav").classList.remove("open");
        }

        $(document).ready(function(){
            // Set the initial state of the toggle based on PHP variable
            var initialStatus = <?php echo $notification_status; ?>;
            if (initialStatus === 1) {
                $('#switch').addClass('toggle-on');
            }

            $('#switch').click(function(e){
                e.preventDefault();
                $(this).toggleClass('toggle-on');

                // Get the new status based on the toggle state
                var status = $(this).hasClass('toggle-on') ? 1 : 0;

                // Send an AJAX request to update the database
                $.ajax({
                    url: 'notification.php',
                    type: 'POST',
                    data: {status: status},
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            console.log("Notification status updated successfully.");
                        } else {
                            console.error("Failed to update notification status:", result.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>
