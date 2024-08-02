<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // User is not logged in as admin, redirect to login page
    header("Location: ../employee.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
	<link rel="icon" type="image/png" sizes="32x32" href="../images/Favicon.png">
    <link rel="stylesheet" type="text/css" href="styles/admin-style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a1a; 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: rgba(204, 204, 204, 0.97); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            transition: box-shadow 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4);
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 30px;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .menu-link {
            display: block;
            padding: 15px 30px;
            margin-bottom: 20px;
            background-color: #4CAF50;
            border-radius: 5px;
            text-decoration: none;
            color: #ffffff;
            font-size: 18px;
            text-align: center;
            transition: background-color 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .menu-link:hover {
            background-color: #45a049;
        }

        .menu-link:last-child {
            margin-bottom: 0;
        }

        .menu-link:active {
            transform: translateY(2px);
            box-shadow: none;
        }

		.btn-34 {
			margin-left: 43rem;
		}
		
		@media (max-width: 768px) {
  
  	.btn-34 {
	margin-left: 15rem;
  }
  
  
    </style>
</head>
<body>
    <div class="container">
        <div>
<button class="btn-34" onclick="window.location.href='../employee.php'"><span>Return</span></button>
        </div>
        <h1>Admin Panel</h1>
        <a class="menu-link" href="addUser.php">Add User</a>
        <a class="menu-link" href="manageUsers.php">Manage Users</a>
        <a class="menu-link" href="addProject.php">Add Project</a>
        <a class="menu-link" href="manageProjects.php">Manage Projects</a>				
    </div>
</body>
</html>
