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

if ( !defined('BLOCK_FILE') ) {
    Header("Location: ../index.php");
    die();
}

global $locale, $oldnum, $storynum, $storyhome, $cookie, $categories, $cat, $prefix, $multilingual, $currentlang, $db, $new_topic, $user_news, $userinfo, $user;

getusrinfo($user);
if ($multilingual == 1) {
    if ($categories == 1) {
    	$querylang = "where catid='$cat' AND (alanguage='$currentlang' OR alanguage='')";
    } else {
    	$querylang = "where (alanguage='$currentlang' OR alanguage='')";
	if ($new_topic != 0) {
	    $querylang .= " AND topic='$new_topic'";
	}
    }
} else {
    if ($categories == 1) {
   	$querylang = "where catid='$cat'";
    } else {
	$querylang = "";
	if ($new_topic != 0) {
	    $querylang = "WHERE topic='$new_topic'";
	}
    }
}
if (isset($userinfo['storynum']) && $user_news == 1) {
    $storynum = $userinfo['storynum'];
} else {
    $storynum = $storyhome;
}
$boxstuff = "<table border=\"0\" width=\"100%\">";
$boxTitle = _PASTARTICLES;
$result = $db->sql_query("SELECT sid, title, time, comments FROM ".$prefix."_stories $querylang ORDER BY time DESC LIMIT $storynum, $oldnum");
$vari = 0;

          if (!isset($mode) || empty($mode)) {
            if(isset($userinfo['umode'])) {
              $mode = $userinfo['umode'];
            } else {
              $mode = "thread";
            }
          }
          if (!isset($order) || empty($order)) {
            if(isset($userinfo['uorder'])) {
              $order = $userinfo['uorder'];
            } else {
              $order = 0;
            }
          }
          if (!isset($thold) || empty($thold)) {
            if(isset($userinfo['thold'])) {
              $thold = $userinfo['thold'];
            } else {
              $thold = 0;
            }
          }
$r_options = "";
$r_options .= "&amp;mode=".$mode;
$r_options .= "&amp;order=".$order;
$r_options .= "&amp;thold=".$thold;

$time2 = "";
while ($row = $db->sql_fetchrow($result)) {
    $sid = intval($row['sid']);
    $title = filter($row['title'], "nohtml");
    $time = $row['time'];
    $comments = intval($row['comments']);
    $see = 1;
    setlocale(LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime2);
    $datetime2 = strftime(_DATESTRING2, mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
    $datetime2 = ucfirst($datetime2);
    if (isset($articlecomm) && ($articlecomm == 1)) {
	$comments = "(".$comments.")";
    } else {
	$comments = "";
    }
    if($time2==$datetime2) {
        $boxstuff .= "<tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"index.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a> $comments</td></tr>\n";
    } else {
        if(empty($a)) {
    	    $boxstuff .= "<tr><td colspan=\"2\"><b>$datetime2</b></td></tr><tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"index.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a> $comments</td></tr>\n";
	    $time2 = $datetime2;
	    $a = 1;
	} else {
	    $boxstuff .= "<tr><td colspan=\"2\"><b>$datetime2</b></td></tr><tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"index.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a> $comments</td></tr>\n";
	    $time2 = $datetime2;
	}
    }
    $vari++;
    if ($vari==$oldnum) {
	if (isset($userinfo['storyhome'])) {
	    $storynum = $userinfo['storyhome'];
	} else {
	    $storynum = $storyhome;
	}
	$min = $oldnum + $storynum;
	$dummy = 1;
    }
}

if ((isset($dummy)) && ($dummy == 1) && is_active("Stories_Archive")) {
    $boxstuff .= "</table><br><a href=\"index.php?name=Stories_Archive\"><b>"._OLDERARTICLES."</b></a>\n";
} else {
    $boxstuff .= "</table>";
}

if ((isset($see)) && ($see == 1)) {
    $content = $boxstuff;
}

?>