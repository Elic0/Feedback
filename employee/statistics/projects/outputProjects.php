<?php
include '../../../connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Projects</title>
<style>
button.button {
    display: inline-block;
    border-radius: 4px;
    background-color: #4F5162;
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 17px;
    padding: 14px;
    width: 100px;
    transition: all 0.5s;
    cursor: pointer;
    margin: 2px;
}

button.button span {
    cursor: pointer;
    display: inline-block;
    position: relative;
    transition: 0.5s;
}

button.button span:after {
    content: '»';
    position: absolute;
    opacity: 0;
    top: 0;
    right: -15px;
    transition: 0.5s;
}

button.button:hover span {
    padding-right: 15px;
}

button.button:hover span:after {
    opacity: 1;
    right: 0;
}
</style>
</head>
<body>

<?php
// SQL query to fetch all columns except fromWebsite
$sql = "SELECT projectName, projectOwner, projectOrg, projectDesc, fromWebsite FROM projects";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<table>";
    echo "<tr><th>Project Name</th><th>Project Owner</th><th>Organisation</th><th>Description</th><th>Action</th></tr>";
    // Output each project row
    while ($row = $result->fetch_assoc()) {
        $projectName = htmlentities($row["projectName"], ENT_QUOTES, "UTF-8");
        $projectOwner = htmlentities($row["projectOwner"], ENT_QUOTES, "UTF-8");
        $projectOrg = htmlentities($row["projectOrg"], ENT_QUOTES, "UTF-8");
        $projectDesc = htmlentities($row["projectDesc"], ENT_QUOTES, "UTF-8");
        $fromWebsite = htmlentities($row["fromWebsite"], ENT_QUOTES, "UTF-8");

        echo "<tr>";
        echo "<td>$projectName</td>";
        echo "<td>$projectOwner</td>";
        echo "<td>$projectOrg</td>";
        echo "<td>$projectDesc</td>";
        echo "<td><a href='$fromWebsite' target='_blank'><button class='button'><span>Go To</span></button></a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>"; 
} else {
    echo "No results";
}

$conn->close();
?>

</body>
</html>
