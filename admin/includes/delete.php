<?
$strScriptFilename          = $_SERVER["SCRIPT_FILENAME"];
$strScriptName              = $_SERVER["SCRIPT_NAME"];
$intPositionOfName          = stripos($strScriptFilename, $strScriptName);
$docRoot  = substr($strScriptFilename, 0,($intPositionOfName + 1));
require $docRoot.'wp-load.php';
	global $wpdb;


// SELECT id, email, url, lead, time FROM ". $wpdb->prefix . "cnc_dudapro
if(isset($_POST['id']))
  {
   $id = $_POST['id'];
   $id = mysql_escape_String($id);
   $delquery=mysql_query("delete from " .$wpdb->prefix . "cnc_dudapro where id=$id") or die(mysql_error());
   //echo "Record deleted";

  }

?>