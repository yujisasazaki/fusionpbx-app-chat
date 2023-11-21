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

if (!class_exists('chatwoot_user')) {
    class chatwoot_user {

        private $user_uuid;
        private $user_id;
        private $account_id;
        private $domain_uuid;
        private $access_token;
        private $pubsub_token;
        private $app_name;
        private $app_uuid;

        private function __construct($user_uuid, $user_id, $access_token, $pubsub_token) {

            $this->user_id = $user_id;
            $this->user_uuid = $user_uuid;
            $this->account_id = $_SESSION['chatwoot']['account']['id'];
            $this->domain_uuid = $_SESSION['domain_uuid'];
            $this->access_token = $access_token;
            $this->pubsub_token = $pubsub_token;
            $this->app_name = 'Chatwoot';
            $this->app_uuid = 'bce7464e-7749-4450-8ff9-9d9f0adcad79';
        }

        /**
         * Creates chatwoot user and the account user on Chatwoot and saves the information on Fusion
         * @return chatwoot_user|bool Returns the chatwoot_user on successfull creation or false if encounters any errors
         */
        public static function create($user_uuid, $name, $email, $password, $custom_attributes = NULL) {
            $user_data = self::create_on_chatwoot($name, $email, $password, $custom_attributes);
            if ($user_data === false) {
                return false;
            }

            $success = self::save_on_fusion($user_uuid, $user_data->id, $user_data->access_token, $user_data->pubsub_token);
            if ($success) {
                return new chatwoot_user($user_uuid, $user_data->id, $user_data->access_token, $user_data->pubsub_token);
            } else {
                //deletes chatwoot user if we fail to save on fusion
                delete_account_user($_SESSION['chatwoot']['account']['id'], $user_data->id);
                delete_user($user_data->id);
                return false;
            }
        }

        private static function create_on_chatwoot($name, $email, $password, $custom_attributes) {

            $user_data = create_user($name, $email, $password, $custom_attributes);
            if (!$user_data->id > 0) {
                return false;
            }

            $account_user = create_account_user($_SESSION['chatwoot']['account']['id'], $user_data->id);
            //deletes user if we couldn't create account user
            if (!$account_user->id > 0) {
                delete_user($user_data->id);
                return false;
            }

            return $user_data;
        }

        private static function save_on_fusion($user_uuid, $user_id, $access_token, $pubsub_token) {

            //prepare the array
            $array['chatwoot_user'][0]['user_id'] = $user_id;
            $array['chatwoot_user'][0]['user_uuid'] = $user_uuid;
            $array['chatwoot_user'][0]['account_id'] = $_SESSION['chatwoot']['account']['id'];
            $array['chatwoot_user'][0]['domain_uuid'] = $_SESSION['domain_uuid'];
            $array['chatwoot_user'][0]['access_token'] = $access_token;
            $array['chatwoot_user'][0]['pubsub_token'] = $pubsub_token;

            //add the temporary permission object
            $p = new permissions;
            $p->add('chatwoot_user_add', 'temp');

            //save the data
            $database = new database;
            $database->app_name = 'Chatwoot';
            $database->app_uuid = 'bce7464e-7749-4450-8ff9-9d9f0adcad79';
            $success = $database->save($array);
            $message = $database->message;

            $p->delete('chatwoot_user_add', 'temp');

            return $success;
        }

        public static function delete_user($user_id) {
            
            //delete in chatwoot
            $success = delete_user($user_id);
            if (!$success) {
                return false;
            }
            
            //prepare the array
            $array['chatwoot_user'][0]['user_id'] = $user_id;

            //add the temporary permission object
            $p = new permissions;
            $p->add('chatwoot_user_delete', 'temp');

            //execute delete
            $database = new database;
            $database->app_name = 'Chatwoot';
            $database->app_uuid = 'bce7464e-7749-4450-8ff9-9d9f0adcad79';
            $success = $database->delete($array);
            $message = $database->message;
            unset($array);

            $p->delete('chatwoot_user_delete', 'temp');

            return $success;
        }

        public static function get_user_list($join_mode = 'INNER') {
            $join_mode = $join_mode === 'INNER' ? 'INNER' : 'LEFT';

            $sql = "SELECT \n";
            $sql .= "	u.username, \n";
            $sql .= "	u.user_uuid, \n";
            $sql .= "   c.user_id \n";
            $sql .= "FROM \n";
            $sql .= "	v_users as u \n";
            $sql .= $join_mode." JOIN \n";
            $sql .= "   v_chatwoot_user as c \n";
            $sql .= "ON \n";
            $sql .= "(\n";
            $sql .= "   u.user_uuid = c.user_uuid \n";
            $sql .= ")\n";
            $sql .= "WHERE \n";
            $sql .= "   u.domain_uuid = :domain_uuid \n";

            $parameters['domain_uuid'] = $_SESSION['domain_uuid'];
            $database = new database;
            $result = $database->select($sql, $parameters, 'all');

            return $result;
        }

        public static function is_chatwoot_user($user_uuid) {
            $sql = "SELECT \n";
            $sql .= "	1 \n";
            $sql .= "FROM \n";
            $sql .= "	v_chatwoot_user \n";
            $sql .= "WHERE \n";
            $sql .= "   user_uuid = :user_uuid \n";

            $parameters['user_uuid'] = $user_uuid;
            $database = new database;
            $result = $database->select($sql, $parameters, 'row');

            return $result;
        }

        public static function get_user_by_uuid($user_uuid) {
            $sql = "SELECT \n";
            $sql .= "	c.user_id, \n";
            $sql .= "   c.user_uuid, \n";
            $sql .= "	c.account_id, \n";
            $sql .= "	c.domain_uuid, \n";
            $sql .= "	c.access_token, \n";
            $sql .= "	c.pubsub_token, \n";
            $sql .= "	u.username \n";
            $sql .= "FROM \n";
            $sql .= "	v_chatwoot_user as c \n";
            $sql .= "INNER JOIN \n";
            $sql .= "   v_users as u \n";
            $sql .= "USING \n";
            $sql .= "   (user_uuid) \n";
            $sql .= "WHERE \n";
            $sql .= "	user_uuid = :user_uuid \n";

            $parameters['user_uuid'] = $user_uuid;
            $database = new database;
            $result = $database->select($sql, $parameters, 'row');

            return $result;
        }
    }
}