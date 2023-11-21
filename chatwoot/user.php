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

//add multi-lingual support
$language = new text;
$text = $language->get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check permissions
    if (permission_exists('chatwoot_edit')) {
        //access granted
    }
    else {
        echo "access denied";
        exit;
    }
    //validate the token
    $token = new token;
    if (!$token->validate($_SERVER['PHP_SELF'])) {
        message::add($text['message-invalid_token'],'negative');
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    if ($_POST['action'] === 'POST') {
        $user_uuid = $_POST['user_uuid'];
        $is_chatwoot_user = chatwoot_user::is_chatwoot_user($user_uuid);

        if (!$is_chatwoot_user) {
            $sql = "SELECT \n";
            $sql .= "   username, \n";
            $sql .= "   user_email \n";
            $sql .= "FROM \n";
            $sql .= "   v_users \n";
            $sql .= "WHERE \n";
            $sql .= "   user_uuid = :user_uuid";

            $parameters['user_uuid'] = $user_uuid;
            $database = new database;
            $user = $database->select($sql, $parameters, 'row');

            $chatwoot_user = chatwoot_user::create($user_uuid, $user['username'], $user['user_email'], generate_special_password());
            if ($chatwoot_user === false) {
                message::add($text['message-add_failed'], 'negative');
            } else {
                message::add($text['message-add'], 'positive');
            }
        }
        header('Location: /app/chatwoot');
        exit;
    }

    if ($_POST['action'] === 'DELETE') {
        foreach ($_POST['users'] as $id => $uuid) {
            $success = chatwoot_user::delete_user($id);
            if (!$success) {
                $errors[] = $id;
            }
        }
        if (!empty($errors)) {
            message::add($text['message-delete_failed']." (".implode(", ", $errors).")", 'negative');
        } else {
            message::add($text['message-delete'], 'positive');
        }
        header('Location: /app/chatwoot');
        exit;
    }
}

//check permissions
if (permission_exists('chatwoot_view')) {
    //access granted
}
else {
    echo "access denied";
    exit;
}

//load chatwoot user
$user_uuid = $_GET['user_uuid'];
if (!is_uuid($user_uuid)) {
    header('Location: /app/chatwoot');
    exit;
}
$user = chatwoot_user::get_user_by_uuid($user_uuid);
if ($user === false) {
    header('Location: /app/chatwoot');
    exit;
}

//show content
$document['title'] = $text['title-user'];
require_once "resources/header.php";
?>
<div class="action_bar">
	<div class="heading">
		<b><?= $user['username'] ?></b>
	</div>
	<div class="actions">
        <?= button::create(['type'=>'button','label'=>$text['button-back'],'icon'=>$_SESSION['theme']['button_icon_back'],'id'=>'btn_back','link'=>'index.php']) ?>
	</div>
</div>
<form>
    <div class="field">
        <label for="user_id"><?= $text['label-id'] ?></label>
        <input id="user_id" value=<?= $user['user_id'] ?>>
    </div>
    <div class="field">
        <label for="access_token"><?= $text['label-access_token'] ?></label>
        <input id="access_token" value=<?= $user['access_token'] ?>>
    </div>
    <div class="field">
        <label for="pubsub_token"><?= $text['label-pubsub_token'] ?></label>
        <input id="pubsub_token" value=<?= $user['pubsub_token'] ?>>
    </div>
</form>
<?php 
//include the footer
require_once "resources/footer.php";