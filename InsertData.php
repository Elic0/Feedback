<?php
// Function to get operating system based on user agent string
function getOS() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS";
    $os_array = array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/macintosh/i'          =>  'Macintosh', 
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
            break;
        }
    }

    return $os_platform;
}

// Function to get browser based on user agent string
function getBrowser() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie|trident/i'       => 'Internet Explorer',
        '/firefox/i'            => 'Firefox',
        '/edge/i'               => 'Edge',
        '/opera/i'              => 'Opera',
        '/opr/i'                => 'Opera',
        '/brave/i'              => 'Brave',
        '/chrome/i'             => 'Chrome',
        '/safari/i'             => 'Safari'
    );

    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
            break;
        }
    }

    return $browser;
}

// Collect data from the form
$topic = $_POST['subject']; 
$email = $_POST['email'];
$message = $_POST['feedback'];
$allowContact = isset($_POST['contactCheckbox']) ? '1' : '0';

// Get the value of fromWebsite directly from the URL
$fromWebsite = isset($_POST['fromWebsite']) ? $_POST['fromWebsite'] : 'Unknown';
$userOS = getOS();
$userBrowser = getBrowser(); // Get user's browser

// Current date and time
$date = date('Y-m-d');

// Database details
$servername = "192.168.116.50";
$username = "feedback";
$password = "password1"; 
$dbname = "feedbackDB";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement with parameterized values
$sql = "INSERT INTO feedbackInfo (topic, mail, feedback, allowContact, userOS, userBrowser, fromWebsite, time) VALUES (?,?,?,?,?,?,?,?)";

// Prepare a prepared statement
$stmt = $conn->prepare($sql);

// Bind parameters to the SQL statement
$stmt->bind_param("ssssssss", $topic, $email, $message, $allowContact, $userOS, $userBrowser, $fromWebsite, $date);

// Execute the prepared statement
if ($stmt->execute()) {
    $response = array(
        "success" => true,
        "message" => "New record created successfully"
    );
} else {
    $error_message = "Error: " . $sql . " " . $conn->error;
    $response = array(
        "success" => false,
        "message" => $error_message
    );
}

// Close the prepared statement and the connection
$stmt->close();
$conn->close();

// Output the response
echo json_encode($response);

// Email sending part starts here
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('PHPMailer/src/Exception.php');
require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fromWebsite = $_POST['fromWebsite'];

    // Fetch the project details
    include 'connection.php';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Modified SQL query to use JOIN
    $sql = "SELECT u.username AS email, p.projectName 
            FROM userProjects p
            JOIN login u ON p.username = u.username
            WHERE p.fromWebsite = ? AND u.notification = 1";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("MySQL prepare statement error: " . $conn->error);
    }

    $stmt->bind_param('s', $fromWebsite);
    $stmt->execute();
    $stmt->bind_result($recipient, $projectName);

    $recipients = [];
    while ($stmt->fetch()) {
        $recipients[] = $recipient;
    }

if (!empty($recipients) && $projectName) {
    $subject = "New feedback for " . $projectName . " - Topic: $topic";
    $message = "You have received new feedback on " . $projectName . "<br><br>Check it out here: <a href='https://feedback.socdata.dk/'>https://feedback.socdata.dk/</a>";



    $result = true;
    foreach ($recipients as $recipient) {
        if (!send_mail($recipient, $subject, $message)) {
            $result = false;
            break;
        }
    }

        if ($result) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "Project or members not found.";
    }
}

function send_mail($recipient, $subject, $message) {
    $recipient = utf8_decode($recipient);
    $subject = utf8_decode($subject);
    $message = utf8_decode($message);

    // NEW PHPMAILER instance
    $mail = new PHPMailer;

    // Enable verbose debug output
    $mail->SMTPDebug = 2;
    $mail->isSMTP();

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Mail server (relay in this case)
    $mail->Host = '185.22.74.33';
    $mail->Port = 25; // UNSECURE
    //$mail->Port = 587; //tls
    //$mail->Port = 465; // ssl
    // From and To address
    $mail->setFrom('hotskp@mercantec.dk', 'Feedback');
    $mail->addAddress($recipient, 'soc-feedback@edu.mercantec.dk');

    // subject and message
    $mail->Subject = $subject;
    $mail->msgHTML($message);

    // Error catching
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}

// Close the connection to the database
$conn->close();
?>
