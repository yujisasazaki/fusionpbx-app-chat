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

//get list of users
if ($chatwoot_account_exists) {
    $user_list = chatwoot_user::get_user_list('LEFT');
}

//add multi-lingual support
$language = new text;
$text = $language->get();

//create token
$object = new token;
$token = $object->create('/app/chatwoot/user.php');

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
<br />
<br />
<div class="action_bar">
	<div class="heading">
		<b><?= $text['label-agents'] ?> (<?= count($user_list) ?>)</b>
	</div>
	<div class="actions">
        <?= button::create(['type'=>'button','label'=>$text['button-delete'],'icon'=>$_SESSION['theme']['button_icon_delete'],'id'=>'btn_delete','name'=>'btn_delete','style'=>'display: none;']) ?>	
	</div>
</div>
<br />
<form action="/app/chatwoot/user.php" method="POST">
	<input type="hidden" id="action" name="action" value="POST">
	<table class="list">
		<tbody>
			<tr class="header">
				<th class="checkbox"><input type="checkbox" id="checkbox_all"></th>
				<th><?= $text['label-name'] ?></th>
				<th><?= $text['label-id'] ?></th>
			</tr>
			<?php foreach ($user_list as $user): ?>
			<tr class="list-row" <?= ($user['user_id'] > 0 ? 'href=/app/chatwoot/user.php?user_uuid='.$user['user_uuid'] : '') ?> >
				<td class="checkbox"><input type="checkbox" name="users[<?=$user['user_id']?>]" value ="<?=$user['user_uuid']?>"></td>
				<td><?= $user['username'] ?></td>
				<td><?= ($user['user_id'] > 0 ? $user['user_id'] : new_user_button($user['user_uuid'])) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</form>
<script>
const checkbox_all_elem = document.getElementById('checkbox_all');
const checkboxes = document.querySelectorAll('input[type=checkbox]');
const button_delete_elem = document.getElementById('btn_delete');

checkbox_all_elem.addEventListener('click', () => list_all_toggle());
checkboxes.forEach((checkbox) => {
	checkbox.addEventListener('click', checkbox_on_change);
});
button_delete_elem.addEventListener('click', () => submit_delete());

function submit_delete() {
	const form = document.querySelector('form');
	const action = document.getElementById('action');
	action.value = "DELETE";
	form.submit();
}
</script>
<?php
//include the footer
require_once "resources/footer.php";

function new_user_button($user_uuid) {
	return button::create([
		'type' => 'submit',
		'icon' => $_SESSION['theme']['button_icon_add'],
		'value' => $user_uuid,
		'name' => 'user_uuid'
	]);
}