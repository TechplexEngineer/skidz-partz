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

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

switch($op) {

    case "modules":
    case "module_status":
    case "module_edit":
    case "module_edit_save":
    case "home_module":
    include("admin/modules/modules.php");
    break;

}

?>