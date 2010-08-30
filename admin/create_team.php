<?php if (!isset($_SESSION)) { session_start(); } ?>

<?php
if (!isset($_SESSION['userid'])) {
  echo ("Sorry, you need to <a href='../login.php'\">login</a> to access this page.");
  exit;
} 
?>

<?php include '../header.inc.php'; ?>

<?php include '../login.inc.php'; ?>

<h1>CoDev Admin : Team Creation</h1>

<script language="JavaScript">

function addTeam(){
   // check fields
   foundError = 0;
   msgString = "Les champs suivants ont &eacute;t&eacute; oubli&eacute;s:\n\n"
       
   if (0 == document.forms["teamCreationForm"].team_name.value)     { msgString += "Team Name\n"; ++foundError; }
   if (0 == document.forms["teamCreationForm"].team_desc.value)     { msgString += "Team Description\n"; ++foundError; }
   if (0 == document.forms["teamCreationForm"].teamleader_id.value) { msgString += "Team Leader\n";  ++foundError; }
                  
   if (0 == foundError) {
     document.forms["teamCreationForm"].action.value="addTeam";
     document.forms["teamCreationForm"].submit();
   } else {
     alert(msgString);    
   }    
 }



</script>

<div id="content">


<?php 
include_once "../constants.php";
include_once "../tools.php";
include_once "../auth/user.class.php";


// -----------------------------
function displayCreateTeamForm() {
  echo("<h1>New Team Creation</h1>\n");
        
#  echo("<form action='edit_team.php' method='post' name='teamCreationForm'>\n");
  echo("<form action='create_team.php' method='post' name='teamCreationForm'>\n");
  echo("Team Name: <input name='team_name' type='text' id='team_name'>\n");
  
  $query = "SELECT DISTINCT id, username, realname FROM `mantis_user_table` ORDER BY username";
  
  
  echo "Team Leader : <select name='teamleader_id'>\n";
  echo "<option value='0'></option>\n";
   
  $result = mysql_query($query) or die("Query failed: $query");
  while($row = mysql_fetch_object($result))
  {  echo "<option value='".$row->id."'>$row->username</option>\n";
  }
  echo "</select>\n";
  
  echo("Description: <input name='team_desc' type='text' id='team_desc'>\n");
  
  echo "<input type=button value='Create Team' onClick='javascript: addTeam()'>\n";
  
  #echo("<input type='submit' name='Submit' value='Create Team'>\n");
     
  echo "<input type=hidden name=action      value=noAction>\n";
  echo "<input type=hidden name=currentForm value=teamCreationForm>\n";
  echo "<input type=hidden name=nextForm    value=teamCreationForm>\n";
     
  echo("</form>\n");
}







// ================ MAIN =================

$link = mysql_connect($db_mantis_host, $db_mantis_user, $db_mantis_pass) 
  or die("Impossible de se connecter");
mysql_select_db($db_mantis_database) or die("Could not select database");


// ---- if not codev admin (team 3) then stop now.
// REM: who is allowed to create a new team ? anyone ?
// TODO replace 3 with a query to codev admin teamId
#$session_user = new User($_SESSION['userid']);
#if (false == $session_user->isTeamMember(3)) {
#  echo ("Sorry, you need to be Codev Administrator to access this page.");
#  exit;
#}

$action = $_POST[action];
$team_name = $_POST[team_name];
$team_desc = $_POST[team_desc];
$teamleader_id = $_POST[teamleader_id];

echo "action = $action<br/>";

if ("addTeam" == $action) {
	echo "Create $team_name !<br/>";
	
   // --- save to DB
   $query = "INSERT INTO `codev_team_table`  (`name`, `description`, `leader_id`) VALUES ('$team_name','$team_desc','$teamleader_id');";
   mysql_query($query) or die("Query failed: $query");
	
   
   // --- add default SuiviOp project
   $query = "SELECT id FROM `codev_team_table` WHERE name = '$team_name';";
   $result = mysql_query($query) or die("Query failed: $query");
   $team_id = (0 != mysql_num_rows($result)) ? mysql_result($result, 0) : "-1";
   
   echo "teamId = $team_id !<br/>";
   
   if (-1 != $team_id) {
	   // TODO replace 11 par SuiviOp
	   // TODO replace 1 with  codev_team_project_type_table 
	   $query = "INSERT INTO `codev_team_project_table`  (`project_id`, `team_id`, `type`) VALUES ('11','$team_id','1');";
	   mysql_query($query) or die("Query failed: $query");
   }
   
} elseif ("editTeam" == $action) {
   echo "Create and go to Edit<br/>";

}else if ("noAction" == $action) {
    echo "browserRefresh<br/>";
} else {
	
	// first call, display form
   displayCreateTeamForm();
}

  
  
  






?>
</div>

<?php include '../footer.inc.php'; ?>
