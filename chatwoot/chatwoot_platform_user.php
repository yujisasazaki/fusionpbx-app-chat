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
if (permission_exists('chatwoot_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//add multi-lingual support
$language = new text;
$text = $language->get();

//if the account was not found then create it
if ($_SERVER["REQUEST_METHOD"] === 'POST' && !$platform_user_exists) {
    //validate the token
    $token = new token;
    if (!$token->validate($_SERVER['PHP_SELF'])) {
        message::add($text['message-invalid_token'],'negative');
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }
    $name = $_POST['name'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $user_email = $_POST['user_email'];

    //validate the input
    if (empty($name)) {
        $invalid[] = $text['label-name'];
    }
    if (empty($password)) {
        message::add($text['message-password_blank'], 'negative', 7500);
    }
    if (!valid_email($user_email)) {
        message::add($text['message-invalid_email'], 'negative', 7500);
    }
    if (!empty($password) && $password != $password_confirm) {
        message::add($text['message-password_mismatch'], 'negative', 7500);
    }

    //require passwords with the defined required attributes: length, number, lower case, upper case, and special characters
    if (!empty($password)) {
        $required_length = 12;
        if (strlen($password) < $required_length) {
            $invalid[] = $required_length . " " . $text['label-characters'];
        }
        if (!preg_match('/(?=.*[\d])/', $password)) {
            $invalid[] = $text['label-numbers'];
        }
        if (!preg_match('/(?=.*[a-z])/', $password)) {
            $invalid[] = $text['label-lowercase_letters'];
        }        
        if (!preg_match('/(?=.*[A-Z])/', $password)) {
            $invalid[] = $text['label-uppercase_letters'];
        }
        if (!preg_match('/(?=.*[\W])/', $password)) {
            $invalid[] = $text['label-special_characters'];
        }
    }

    //return if error
    if (message::count() != 0 || !empty($invalid)) {
        if ($invalid) { message::add($text['message-required'].implode(', ', $invalid), 'negative', 7500); }
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    //create the user
    $chatwoot_user = create_user($name, $user_email, $password);
    
    //return if error
    if (!$chatwoot_user->id > 0) {
        message::add($text['message-error_creating_user'], 'negative', 7500);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    //prepare the array
    $array['chatwoot_platform_user'][0]['user_id'] = $chatwoot_user->id;
    $array['chatwoot_platform_user'][0]['access_token'] = $chatwoot_user->access_token;

    //add the temporary permission object
    $p = new permissions;
    $p->add('chatwoot_platform_user_add', 'temp');

    //save the data
    $database = new database;
    $database->app_name = 'Chatwoot';
    $database->app_uuid = 'bce7464e-7749-4450-8ff9-9d9f0adcad79';
    $success = $database->save($array);
    $message = $database->message;
    unset($array);

    $p->delete('chatwoot_platform_user_add', 'temp');

    //if there was an error then delete the user in chatwoot
    if (!$success) {
        delete_user($chatwoot_user->id);
        message::add($text['message-error_creating_user'], 'negative', 7500);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    require "resources/chatwoot.php";
}

//load platform user details
if ($platform_user_exists) {
    $platform_user = get_user($_SESSION['chatwoot_platform_user']['user_id']);
} else {
    //create token
    $object = new token;
    $token = $object->create($_SERVER['PHP_SELF']);
}

//show content
$document['title'] = $text['title-chatwoot_platform_user'];
require_once "resources/header.php";
?>
<form name='frm' id='frm' method='post'>
    <div class="action_bar" id="action_bar">
        <div class="heading">
            <b><?= $text['header-chatwoot_platform_user'] ?></b>
        </div>
        <div class="actions">
            <?= button::create(['type'=>'button','label'=>$text['button-back'],'icon'=>$_SESSION['theme']['button_icon_back'],'id'=>'btn_back','link'=>'index.php']) ?>
            <?php if (!$platform_user_exists): ?>
                <?= button::create(['type'=>'submit','label'=>$text['button-save'],'icon'=>$_SESSION['theme']['button_icon_save'],'id'=>'btn_save','show'=>true]) ?>
            <?php endif; ?>
        </div>
        <div style='clear: both;'></div>
    </div>
    <?= $text['description-chatwoot_platform_user'] ?>
    <br />
    <br />
    <table cellpadding='0' cellspacing='0' border='0' width='100%'>
        <tr>
            <td width='30%' class='vncellreq' valign='top'><?= $text['label-name'] ?></td>
            <td width='70%' class='vtable'>		<input type='text' class='formfld' name='name' id='name' autocomplete='new-password' value='<?= escape($platform_user->name ?? '') ?>' required='required'>
                <input type='text' style='display: none;' disabled='disabled'>
            </td>
        </tr>
        <?php if (!$platform_user_exists): ?>
        <tr>
            <td class='vncellreq' valign='top'><?= $text['label-password'] ?></td>
            <td class='vtable'>
                <input type='password' style='display: none;' disabled='disabled'>
                <input type='password' autocomplete='new-password' class='formfld' name='password' id='password' value="" required='required' onkeypress='show_strength_meter();' onfocus='compare_passwords();' onkeyup='compare_passwords();' onblur='compare_passwords();'>
                <div id='pwstrength_progress' class='pwstrength_progress'></div>
                <br /><?= $text['message-required_password'] ?>
            </td>
        </tr>
        <tr>
            <td class='vncellreq' valign='top'><?= $text['label-confirm_password'] ?></td>
            <td class='vtable'>
                <input type='password' autocomplete='new-password' class='formfld' name='password_confirm' id='password_confirm' value="" required='required' onfocus='compare_passwords();' onkeyup='compare_passwords();' onblur='compare_passwords();'>
                <br /><?= $text['message-green_border_passwords_match'] ?>
            </td>
        </tr>
        <?php endif; ?>
        <?php if ($platform_user_exists): ?>
        <tr>
            <td class='vncellreq'><?= $text['label-access_token'] ?></td>
            <td class='vtable'><input type='text' class='formfld' name='access_token' value='<?= escape($platform_user->access_token ?? '') ?>' required='required'></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class='vncellreq'><?= $text['label-email'] ?></td>
            <td class='vtable'><input type='text' class='formfld' name='user_email' value='<?= escape($platform_user->email ?? '') ?>' required='required'></td>
        </tr>
    </table>
    <?php if (!$platform_user_exists): ?><input type='hidden' name='<?= $token['name'] ?>' value='<?= $token['hash'] ?>'><?php endif; ?>
</form>
<?php if (!$platform_user_exists): ?>
<script>
function compare_passwords() {
    if (document.getElementById('password') === document.activeElement || document.getElementById('password_confirm') === document.activeElement) {
        if ($('#password').val() != '' || $('#password_confirm').val() != '') {
            if ($('#password').val() != $('#password_confirm').val()) {
                $('#password').removeClass('formfld_highlight_good');
                $('#password_confirm').removeClass('formfld_highlight_good');
                $('#password').addClass('formfld_highlight_bad');
                $('#password_confirm').addClass('formfld_highlight_bad');
            }
            else {
                $('#password').removeClass('formfld_highlight_bad');
                $('#password_confirm').removeClass('formfld_highlight_bad');
                $('#password').addClass('formfld_highlight_good');
                $('#password_confirm').addClass('formfld_highlight_good');
            }
        }
    }
    else {
        $('#password').removeClass('formfld_highlight_bad');
        $('#password_confirm').removeClass('formfld_highlight_bad');
        $('#password').removeClass('formfld_highlight_good');
        $('#password_confirm').removeClass('formfld_highlight_good');
    }
}
function show_strength_meter() {
    $('#pwstrength_progress').slideDown();
}
</script>
<?php endif; ?>
<?php
//include the footer
require_once "resources/footer.php";