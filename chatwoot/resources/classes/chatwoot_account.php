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

if (!class_exists('chatwoot_account')) {
	class chatwoot_account {

        private $account_id;
        private $domain_uuid;
        private $app_name;
        private $app_uuid;

        private function __construct($account_id) {
            $this->account_id = $account_id;
            $this->domain_uuid = $_SESSION['domain_uuid'];
            $this->app_name = 'Chatwoot';
            $this->app_uuid = 'bce7464e-7749-4450-8ff9-9d9f0adcad79';
        }

        /**
         * Creates chatwoot account and the account user on Chatwoot and saves the account_id on Fusion
         * @return chatwoot_account|bool Returns the chatwoot_account on successfull creation or false if encounters any errors
         */
        public static function create() {
            $account_id = self::create_on_chatwoot();
            if ($account_id === false) {
                return false;
            }
            $success = self::save_on_fusion($account_id);
            if ($success === false) {
                return false;
            }
            return new chatwoot_account($account_id);
        }

        private static function create_on_chatwoot() {
            $account_id = create_account($_SESSION['domain_name']);
            $account_user = create_account_user($account_id, $_SESSION['chat']['platform_user_id']['numeric'], 'administrator');

            //deletes account if we couldn't create the account user
            if (!$account_user->id > 0) {
                delete_account($account_id);
                return false;
            }
            return $account_id;
        }

        private static function save_on_fusion($account_id) {
            //prepare the array
            $array['chatwoot_account'][0]['account_id'] = $account_id;
            $array['chatwoot_account'][0]['domain_uuid'] = $_SESSION['domain_uuid'];

            //add the temporary permission object
            $p = new permissions;
            $p->add('chatwoot_account_add', 'temp');

            //save the data
            $database = new database;
            $database->app_name = 'Chatwoot';
            $database->app_uuid = 'bce7464e-7749-4450-8ff9-9d9f0adcad79';
            $success = $database->save($array);
            $message = $database->message;
            unset($array);

            $p->delete('chatwoot_account_add', 'temp');

            return $success;
        }

        /**
         * Get the chatwoot_account of the current domain
         * @return chatwoot_account|bool Returns the chatwoot_account or false if encounters any errors
         */
        public static function get_domain_account() {
            $sql = "SELECT \n";
            $sql .= "	account_id \n";
            $sql .= "FROM \n";
            $sql .= "	v_chatwoot_account \n";
            $sql .= "WHERE \n";
            $sql .= "	domain_uuid = :domain_uuid";

            $parameters['domain_uuid'] = $_SESSION['domain_uuid'];
            $database = new database;
            $account_id = $database->select($sql, $parameters, 'column');

            if ($account_id === false) {
                return false;
            }
            return new chatwoot_account($account_id);
        }

        public function get_account_id() {
            return $this->account_id;
        }
    }
}