<?php

######################################################################
# Skidz Partz - Exchange
# ============================================
#
# Copyright (c) 2010 by Dazzle Development Team
# http://www.dazzlecms.com
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

if (!defined('MODULE_FILE')) {
	die("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$sid = intval($sid);
$query = $db->sql_query("SELECT associated FROM ".$prefix."_stories WHERE sid='$sid'");
list($associated) = $db->sql_fetchrow($query);

if (!empty($associated)) {
	Open_Table();
	echo "<center><b>"._ASSOTOPIC."</b><br><br>";
	$asso_t = explode("-",$associated);
	for ($i=0; $i<sizeof($asso_t); $i++) {
		if (!empty($asso_t[$i])) {
		        $query = $db->sql_query("SELECT topicimage, topictext from ".$prefix."_topics WHERE topicid='".$asso_t[$i]."'");
			list($topicimage, $topictext) = $db->sql_fetchrow($query);
			echo "<a href=\"index.php?name=$module_name&new_topic=$asso_t[$i]\"><img src=\"".$tipath."/".$topicimage."\" border=\"0\" hspace=\"10\" alt=\"".$topictext."\" title=\"".$topictext."\"></a>";
		}
	}
	echo "</center>";
	Close_Table();
	echo "<br>";
}

?>