<?php
/*
 FusionPBX
 Version: MPL 1.1

 The contents of this file are subject to the Mozilla Public License Version
 1.1 (the "License"); you may not use this file except in compliance with
 the License. You may obtain a copy of the License at
 http://www.mozilla.org/MPL/

 Software distributed under the License is distributed on an "AS IS" basis,
 WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 for the specific language governing rights and limitations under the
 License.

 The Original Code is FusionPBX

 The Initial Developer of the Original Code is
 Mark J Crane <markjcrane@fusionpbx.com>
 Portions created by the Initial Developer are Copyright (C) 2008-2023
 the Initial Developer. All Rights Reserved.

 Contributor(s):
 Jhonatan Sasazaki <jhonatan.sasazaki@akrasia.com.br> 
 Mark J Crane <markjcrane@fusionpbx.com>
*/

require_once "chatwoot_api_functions.php";

//verify if the platform user exists
$platform_user_exists = false;
if (isset($_SESSION['chatwoot_platform_user']['user_id'])
    && isset($_SESSION['chatwoot_platform_user']['access_token'])
) {
    $platform_user_exists = true;   
} else {
    $sql = "SELECT \n";
    $sql .= "	user_id, access_token \n";
    $sql .= "FROM \n";
    $sql .= "	v_chatwoot_platform_user \n";
    
    $database = new database;
    $result = $database->select($sql, '', 'row');

    if (!empty($result)) {
        $platform_user_exists = true;
        $_SESSION['chatwoot_platform_user']['user_id'] = $result['user_id'];
        $_SESSION['chatwoot_platform_user']['access_token'] = $result['access_token'];
    }
    unset($sql, $database);
}