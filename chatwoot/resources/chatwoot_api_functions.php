<?php

/*
    Chatwoot API functions
*/

if (!function_exists('get_account_users')) {
    function get_account_users($account_id) {
        
        $path = "/platform/api/v1/accounts/".$account_id."/account_users";

        $response = chatwoot_system_request($path, "GET");
        return $response;
    }
}

if (!function_exists('create_account')) {
    function create_account($name) {
        $path = "/platform/api/v1/accounts/";
        
        $body = array(
            'name' => $name,
        );
        
        $response = chatwoot_system_request($path, "POST", json_encode($body));
        $json_response = json_decode($response, true);
        
        $id = $json_response["id"];
        if ($id > 1) {
            return $id;
        } else {
            return false;
        }        
    }
}

if (!function_exists('delete_account')) {
    function delete_account($account_id) {
        $path = "/platform/api/v1/accounts/".$account_id;
        
        $response = chatwoot_system_request($path, "DELETE");

        if ($response === "") {
            return true;
        } else {
            return false;
        }     
    }
}

if (!function_exists('get_user')) {
    function get_user($user_id) {
        $path = "/platform/api/v1/users/".$user_id;

        $response = chatwoot_system_request($path, "GET");
        return json_decode($response);
    }
}

if (!function_exists('create_user')) {
    function create_user($name, $email, $password, $custom_attributes = NULL) {
        $path = "/platform/api/v1/users";

        $body = array(
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'custom_attributes' => $custom_attributes
        );

        $response = chatwoot_system_request($path, "POST", json_encode($body));        
        return json_decode($response);
    }
}

if (!function_exists('delete_user')) {
    function delete_user($id) {
        $path = "/platform/api/v1/users/".$id;

        $response = chatwoot_system_request($path, "DELETE");

        if ($response === "") {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('create_account_user')) {
    function create_account_user($account_id, $user_id, $role = 'agent') {
        $path = "/platform/api/v1/accounts/".$account_id."/account_users";

        $body = array(
            'user_id' => $user_id,
            'role' => $role
        );

        $response = chatwoot_system_request($path, "POST", json_encode($body));
        return json_decode($response);
    }    
}

if (!function_exists('delete_account_user')) {
    function delete_account_user($account_id, $user_id) {
        $path = "/platform/api/v1/accounts/".$account_id."/account_users";

        $body = array(
            'user_id' => $user_id
        );

        $response = chatwoot_system_request($path, "DELETE", json_encode($body));
        return $response;
    }    
}

if (!function_exists('create_inbox')) {
    function create_inbox($id, $name, $channel) {
        $path = "/api/v1/accounts/".$id."/inboxes";

        $body = array(
            'name' => $name,
            'enable_auto_assignment' => false,
            'channel' => $channel
        );

        $response = chatwoot_system_request($path, "POST", json_encode($body));
        return json_decode($response);
    }
}

if (!function_exists('get_all_inbox')) {
    function get_all_inbox($account_id) {
        $path = "/api/v1/accounts/".$account_id."/inboxes";

        $response = chatwoot_system_request($path, "GET");
        return json_decode($response);
    }
}

if (!function_exists('get_inbox')) {
    function get_inbox($account_id, $inbox_id) {
        $path = "/api/v1/accounts/".$account_id."/inboxes/".$inbox_id;

        $response = chatwoot_system_request($path, "GET");
        return json_decode($response);
    }
}

if (!function_exists('update_inbox_agents')) {
    function update_inbox_agents($account_id, $inbox_id, $agent_ids) {
        $path = "/api/v1/accounts/".$account_id."/inbox_members";

        $body = array(
            'inbox_id' => $inbox_id,
            'user_ids' => $agent_ids
        );

        $response = chatwoot_system_request($path, "PATCH", json_encode($body));
        return json_decode($response, true);
    }
}

if (!function_exists('delete_inbox')) {
    function delete_inbox($account_id, $inbox_id) {
        $path = "/api/v1/accounts/".$account_id."/inboxes/".$inbox_id;
        $response = chatwoot_system_request($path, "DELETE");
        if ($response === "") {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('get_inbox_agents')) {
    function get_inbox_agents($account_id, $inbox_id) {
        $path = "/api/v1/accounts/".$account_id."/inbox_members/".$inbox_id;

        $response = chatwoot_system_request($path, "GET");
        return json_decode($response, true);
    }
}

if (!function_exists('chatwoot_system_request')) {
    function chatwoot_system_request($path, $method = "GET", $content = NULL) {

        $token_type = explode("/", $path)[1];

        if ($token_type === "api") {
            $api_access_token = $_SESSION['chatwoot']['platform_user']['access_token'];
        } elseif ($token_type === "platform") {
            $api_access_token = $_SESSION['chat']['platform_access_token']['text'];
        }

        return chatwoot_request($path, $method, $api_access_token, $content);
    }
}

if (!function_exists('chatwoot_agent_request')) {
    function chatwoot_agent_request($path, $method = "GET", $content = NULL) {
        
        $api_access_token = $_SESSION['chatwoot']['user']['access_token'];
        $path = "/api/v1".$path;

        return chatwoot_request($path, $method, $api_access_token, $content);
    }
}

if (!function_exists('chatwoot_request')) {
    function chatwoot_request($path, $method = "GET", $api_access_token, $content = NULL) {

        $url = $_SESSION['chat']['chatwoot_url']['text'].$path;

        $headers = ["Content-type: application/json; charset=utf-8",
                    "api_access_token: ".$api_access_token];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if ($content) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

// https://stackoverflow.com/a/65030840
if (!function_exists('generate_special_password')) {
    function generate_special_password() {
        $digits    = array_flip(range('0', '9'));
        $lowercase = array_flip(range('a', 'z'));
        $uppercase = array_flip(range('A', 'Z')); 
        $special   = array_flip(str_split('!@#$%^&*()_+=-}{[}]|;:<>?/'));
        $combined  = array_merge($digits, $lowercase, $uppercase, $special);

        $response  = str_shuffle(array_rand($digits) .
                                array_rand($lowercase) .
                                array_rand($uppercase) . 
                                array_rand($special) . 
                                implode(array_rand($combined, random_int(9, 13))));
        
        return $response;
    }
}