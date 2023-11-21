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

//includes
require_once dirname(__DIR__, 2) . "/resources/require.php";
require_once "resources/check_auth.php";
require "resources/chatwoot.php";

//check permissions
if (permission_exists('chatwoot_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//verify if the platform user exists
if (!$platform_user_exists) {
    if (permission_exists('chatwoot_admin')) {
        header('Location: chatwoot_platform_user.php');
        exit;
    } else {
        echo "chatwoot not configured, contact your administrator";
        exit;
    }
}

//check if there is a domain account
$chatwoot_account_exists = false;
if (isset($_SESSION['chatwoot']['account']['id'])) {
    $chatwoot_account_exists = true;
} else {
    //create domain chatwoot_account
    $chatwoot_account = chatwoot_account::create();
    if ($chatwoot_account instanceof chatwoot_account) {
        $_SESSION['chatwoot']['account']['id'] = $chatwoot_account->get_account_id();
        $_SESSION['chatwoot']['account']['domain_uuid'] =  $_SESSION['domain_uuid'];
        $chatwoot_account_exists = true;
    } else {
        //error creating chatwoot_account
    }    
}

//add multi-lingual support
$language = new text;
$text = $language->get();

//show content
$document['title'] = $text['title-chatwoot'];
require_once "resources/header.php";
?>
<div class="action_bar" id="action_bar">
    <div class="heading">
        <?php if (!$chatwoot_account_exists): ?>
            <b>Error getting Chatwoot Account</b>    
        <?php else : ?>
            <b><?= $text['title-chatwoot'] ?> ID: <?= $_SESSION['chatwoot']['account']['id'] ?></b>
        <?php endif; ?>
    </div>
    <div class="actions">
        <?php if (permission_exists('chatwoot_admin')): ?>
            <?= button::create(['type'=>'button','label'=>$text['title-chatwoot_platform_user'],'link'=>'chatwoot_platform_user.php']); ?>
        <?php endif; ?>
    </div>
</div>
<?php
//include the footer
require_once "resources/footer.php";