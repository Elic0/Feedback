<?php
session_start();

if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="da">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mercantec - Medarbejder</title>
    <link rel="icon" type="image/png" sizes="32x32" href="images/Favicon.png">
    <link rel="stylesheet" href="/employee/styles/employee.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <?php include 'feedbackButton/feedback.php'; ?>

    <style>
        .projects-section,
        .statistics-section,
        .feedback-chart-section {
            border-radius: 10px;
            background-color: #6B6ECC;
            background: linear-gradient(45deg, rgba(4, 5, 29, 0.96) 0%, #2b566e 100%);
            text-align: center;
            transition: background-color 1s;
        }

        .projects-section:hover,
        .statistics-section:hover,
        .feedback-chart-section:hover {
            transform: translateY(-0.5px);
            box-shadow: 0 10px 15px -3px rgba(33, 150, 243, 0.98), 0 4px 6px -4px rgba(33, 150, 243, 0.98);
            background-color: #6B6ECC;
            transition: box-shadow 1s;
        }

        header nav .button-container button:hover,
        .sidebar-container button:hover {
            background-color: #4C6885;
        }

        /* Adjust the height of chart container */
        .chart-container {
            position: relative;
            width: 100%;
            height: 600px; /* Adjust height as needed */
        }
    </style>

</head>

<body class="dark-theme">

    <?php include 'header.php'; ?>

    <main>
        <section class="projects-section">
            <h1>Your Projects</h1>
            <hr class="underline">
            <h2><?php include 'employee/yourProjects.php'?></h2>
        </section>

        <section class="statistics-section">
            <h1>Statistics</h1>
            <hr class="underline">
            <h6>New Feedback:</h6>
            <h2 id="newFeedbackCount"><?php include 'employee/statisticsOutput.php'?></h2>
            <h6>Feedback:</h6>
            <h2 id="totalFeedbackCount"><?php include 'employee/statisticsOutput.php'?></h2>
            <h6>Total Projects:</h6>
            <h2 id="allProjectsCount"><?php include 'employee/statisticsOutput.php'?></h2>
        </section>

        <section class="feedback-chart-section">
            <h1>Feedback Overview</h1>
            <hr class="underline">
            <div class="chart-container">
                <canvas id="feedbackChart"></canvas>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        fetch('fetchFeedbackData.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Fetched data:', data); // Debug: Log fetched data
                const labels = data.map(item => item.fromWebsite);
                const feedbackCounts = data.map(item => item.feedbackCount);
                const colors = data.map(item => item.color);

                const ctx = document.getElementById('feedbackChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Feedback Count',
                            data: feedbackCounts,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(err => {
                console.error('Error fetching feedback data:', err); // Debug: Log errors
            });
    </script>

    <script>
        fetch('employee/statisticsOutput.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('newFeedbackCount').textContent = data.newFeedbackCount;
                document.getElementById('totalFeedbackCount').textContent = data.totalFeedbackCount;
                document.getElementById('allProjectsCount').textContent = data.totalProjectsCount;
            })
            .catch(err => console.error('Error fetching statistics:', err));
    </script>
    
    <script>
        // JavaScript to handle redirection to projectFeedback.php on project click
        document.querySelectorAll('.projects-section button').forEach(item => {
            item.addEventListener('click', event => {
                // Redirect to projectFeedback.php in employee/projects/ directory
                window.location.href = "employee/projects/projectFeedback.php?project=" + encodeURIComponent(item.value);
            });
        });
    </script>

    <script src="employee/javascript/employee.js"></script>

</body>

</html>
