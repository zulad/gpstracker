<?php /* GPS LOCATOR */
$usr = null;
$timestp = mktime();

$con = mysql_connect("","",""); // <!!--- to set
if (!$con)
  {
	die('Could not connect: ' . mysql_error());
  }

mysql_select_db("", $con); // <!!--- to set

//
$userid = $_POST['userid'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$accuracy = $_POST['accuracy'];
$timestamp = $_POST['timestamp'];
$heading = $_POST['heading'];
$speed = $_POST['speed'];
$altitude = $_POST['altitude'];
$altitudeAccuracy = $_POST['altitudeAccuracy'];

$sql="INSERT INTO `my_location` (
	`dump`,
	`latitude`,
	`longitude`,
	`account`
) VALUES (
	'".addslashes($userid)
	.'-@@-'.addslashes($latitude)
	.'-@@-'.addslashes($longitude)
	.'-@@-'.addslashes($timestp)
	.'-@@-'.addslashes($accuracy)
	.'-@@-'.addslashes($timestamp)
	.'-@@-'.addslashes($heading)
	.'-@@-'.addslashes($speed)
	.'-@@-'.addslashes($altitude)
	.'-@@-'.addslashes($altitudeAccuracy)."',
	'".addslashes($latitude)."',
	'".addslashes($longitude)."',
	'".addslashes($userid)."'
)";

/* no output !! */
if(mysql_query($sql))
{
	echo "1";
}

?>
