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
	die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$pagetitle = "- "._ACTIVETOPICS."";
include("header.php");
Open_Table();

global $db, $prefix, $tipath;
$ThemeSel = get_theme();
$sql = "SELECT t.*, s.* FROM ".$prefix."_topics t LEFT JOIN ".$prefix."_stories s ON (s.topic = t.topicid) GROUP BY t.topicid, t.topicimage, t.topictext ORDER BY t.topictext";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    $output = "<center><font class=\"title\"><b>"._ACTIVETOPICS."</b></font><br>\n";
    $output .= "<font class=\"content\">"._CLICK2LIST."</font><br><br>\n";
	if(is_active('Search'))
	{
       $output .= "<form action=\"index.php?name=Search\" method=\"post\">";
       $output .= "<input type=\"name\" name=\"query\" size=\"30\">  ";
       $output .= "<input type=\"submit\" value=\""._SEARCH."\">";
       $output .= "</form>";
	}
	   $output .= "</center><br><br>";
    echo $output;
    while ($row = $db->sql_fetchrow($result)) {
        $topicid = intval($row['topicid']);
        $topicimage = stripslashes($row['topicimage']);
        $topictext = stripslashes(check_html($row['topictext'], "nohtml"));
        if(file_exists("themes/".$ThemeSel."/images/topics/".$topicimage)) {
          $t_image = "themes/".$ThemeSel."/";
        } else {
          $t_image = "";
        }
        $t_image = $t_image.$tipath.$topicimage;
        title($topictext);
        $output = "<table border=\"1\" width=\"100%\" align=\"center\" cellpadding=\"2\">\n";
        $output .= "<tr><td valign=\"top\" width=\"25%\">";
        $output .= "<a href=index.php?name=News&amp;topic=$topicid><img src=\"$t_image\" border=\"0\" alt=\"$topictext\" title=\"$topictext\" hspace='5' vspace='5'></a><br><br>\n";
        $output .= "<font class=\"content\">";
        $output .= "<big><strong>&middot;</strong></big> <b>"._TOPIC.":</b> $topictext<br>\n";
        $output .= "<big><strong>&middot;</strong></big> <b>"._TOTNEWS.":</b> ".$row['sid']."<br>\n";
        $output .= "<big><strong>&middot;</strong></big> <b>"._TOTREADS.":</b> ".(isset($row['counter']) ? $row['counter'] : 0)."</font>";
        $output .= "</td><td valign=\"top\">";
        echo $output;
        
        if ($row['sid'] > 0) {
            $sql2 = "SELECT s.sid, s.catid, s.title, c.title AS cat_title FROM ".$prefix."_stories s LEFT JOIN ".$prefix."_stories_cat c ON s.catid=c.catid WHERE s.topic='$topicid' ORDER BY s.sid DESC LIMIT 0,10";
			$result2 = $db->sql_query($sql2);
            while ($row2 = $db->sql_fetchrow($result2)) {
                $cat_link = (intval($row2['catid']) > 0) ? "<a href=\"index.php?name=News&amp;file=categories&amp;op=newindex&amp;catid=".intval($row2['catid'])."\"><strong>".stripslashes(check_html($row2['cat_title'], "nohtml"))."</strong></a>: " : "";
                echo "<img src=\"images/arrow.gif\" border=\"0\" alt=\"\" /> $cat_link<a href=\"index.php?name=News&amp;file=article&amp;sid=".intval($row2['sid'])."\">".htmlentities($row2['title'])."</a><br>";
            }
            if ($row['sid'] > 10) {
                echo "<div align=\"right\"><strong>&middot;</strong> <a href=\"index.php?name=News&amp;new_topic=".$topicid."\"><strong>"._MORE." --&gt;</strong></a></div>";
            }
        } else {
            echo "<i>"._NONEWSYET."</i>";
        }
        echo "</td></tr></table><br><br>";
    }
} else {
  echo "<i>"._NONEWSYET."</i>";
}

Close_Table();
include("footer.php");

?>