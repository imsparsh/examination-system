<?php
ob_start();
session_start();
extract($_POST);
extract($_GET);
extract($_SESSION);

//setting timezones 
$offset = '+05:30'; // GMT offset
$is_DST = FALSE; // observing daylight savings?
$timezone_name = timezone_name_from_abbr('', $offset * 3600, $is_DST); // e.g. "America/New_York"
 
//date_default_timezone_set($timezone_name);

date_default_timezone_set("America/New_York"); 

     $timestamp = time();
     $datum = date(" H:i:s | m-d-Y (D) ",$timestamp);

//database credentials
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','examination');

//application address
define('DIR','http://myexam.com/');
define('SITEEMAIL','mail@me.com');

try {

	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
include('classes/user.php');
$user = new User($db); 
?>
