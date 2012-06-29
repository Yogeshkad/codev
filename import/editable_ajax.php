<?php

include_once('../include/session.inc.php');
/*
  This file is part of CodevTT

  CodevTT is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  CodevTT is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with CodevTT.  If not, see <http://www.gnu.org/licenses/>.
 */

require ('../path.inc.php');
require ('super_header.inc.php');

require_once "project_cache.class.php";
require_once "team_cache.class.php";

/*
 * NOTE: this file returns the text to set in the datatable field
 * depending on the item selected in the jeditable component (combobox)
 * 
 */




$teamid    = isset($_GET['teamid']) ? $_GET['teamid'] : '0';
$column    = isset($_GET['column']) ? $_GET['column'] : 'unknown_column';



if ('command' == $column) {

   $command_id = $_POST['value'];

   $cmd = CommandCache::getInstance()->getCommand($command_id);

   echo $cmd->getName();
}

if ('category' == $column) {

   $category_id = $_POST['value'];

   echo Project::getCategoryName($category_id);
}

if ('targetVersion' == $column) {

   #$projectid        = isset($_GET['projectid']) ? $_GET['projectid'] : '0';
   $targetVersion_id = $_POST['value'];

   echo Project::getProjectVersionName($targetVersion_id);
}

if ('userName' == $column) {

   $user_id = $_POST['value'];

   // -- encode combobox (select) elements
   $array = array();
   $array['1'] = 'userName 1';
   $array['2'] = 'userName 2';
   $array['3'] = 'userName 3';

   $selected = isset($_GET['selected']) ? $_GET['selected'] : NULL;

   if (isset($_GET['selected'])) {
      $array['selected'] = $_GET['selected'];
   }
   echo $array[$user_id];
}

if ('mgrEffortEstim' == $column) {

   echo $_POST['value'];
}
if ('effortEstim' == $column) {

   echo $_POST['value'];
}

?>