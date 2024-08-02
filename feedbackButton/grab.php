<?php
$servername = "192.168.116.50";
$username = "feedback";
$password = "password1"; 
$dbname = "feedbackDB"; 

// Opret forbindelse til databasen
$conn = new mysqli($servername, $username, $password, $dbname);

// Tjek forbindelse
if ($conn->connect_error) {
    die("Forbindelse mislykkedes: " . $conn->connect_error);
}

// Funktion til at hente henvisende URL
function getReferringURL() {
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "Unknown";
}

// Funktion til at detektere operativsystemet
function getOS() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $os_platform = "Unknown OS";

    // Associativt array med regulære udtryk og tilsvarende operativsystemer
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
        '/macintosh/i'          =>  'Macintosh', // Identificerer alle Mac-enheder
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

// Tjek om FromWebsite er sat i URL'en
if(isset($_GET['FromWebsite'])) {
    // Hent værdien af FromWebsite fra URL'en
    $fromWebsite = $_GET['FromWebsite'];

    // Forbered en SQL-forespørgsel for at kontrollere om FromWebsite findes i projects-tabellen
    $stmt = $conn->prepare("SELECT COUNT(*) FROM projects WHERE fromWebsite = ?");
    $stmt->bind_param("s", $fromWebsite);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Hvis FromWebsite findes i databasen, fortsæt som normalt
    if($count > 0) {
        // Fortsæt med din eksisterende kode for at hente henvisende URL og operativsystem
        $referring_url = getReferringURL();
        $userOS = getOS();
    } else {
        // Hvis FromWebsite ikke findes i databasen, omdiriger brugeren til index.php uden FromWebsite i URL'en
        $redirectURL = "index.php";
        if(isset($_GET['UserOS'])) {
            $redirectURL .= "?UserOS=" . $_GET['UserOS'];
        }
        header("Location: $redirectURL");
        exit;
    }
} else {
    // Hvis FromWebsite ikke er sat i URL'en, fortsæt med din eksisterende kode
    // Fortsæt med din eksisterende kode for at hente henvisende URL og operativsystem
    $referring_url = getReferringURL();
    $userOS = getOS();
}
?>
