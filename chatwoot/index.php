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

//check permissions
if (permission_exists('chatwoot_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//load chatwoot_account
$chatwoot_account = chatwoot_account::get_domain_account();
if ($chatwoot_account === false) {
    $not_found = true;
} else {
    $account_id = $chatwoot_account->get_account_id();
}

//add multi-lingual support
$language = new text;
$text = $language->get();

//show content
$document['title'] = $text['title-chatwoot'];
require_once "resources/header.php";

?>

<?php if ($not_found): ?>
    <b>Error getting Chatwoot Account</b>
<?php else : ?>
    <div class="heading">
        <b><?= $text['title-chatwoot'] ?> ID: <?= $account_id ?></b>
    </div>
<?php endif; ?>

<?php
//include the footer
require_once "resources/footer.php";