<?php

$apps[$x]['name'] = "Chatwoot";
$apps[$x]['uuid'] = "bce7464e-7749-4450-8ff9-9d9f0adcad79";
$apps[$x]['category'] = "CRM";
$apps[$x]['subcategory'] = "";
$apps[$x]['version'] = "0.1";
$apps[$x]['license'] = "Mozilla Public License 1.1";
$apps[$x]['url'] = "";
$apps[$x]['description']['en-us'] = "Chatwoot";
$apps[$x]['description']['en-gb'] = "Chatwoot";
$apps[$x]['description']['ar-eg'] = "Chatwoot";
$apps[$x]['description']['de-at'] = "Chatwoot";
$apps[$x]['description']['de-ch'] = "Chatwoot";
$apps[$x]['description']['de-de'] = "Chatwoot";
$apps[$x]['description']['es-cl'] = "Chatwoot";
$apps[$x]['description']['es-mx'] = "Chatwoot";
$apps[$x]['description']['fr-ca'] = "Chatwoot";
$apps[$x]['description']['fr-fr'] = "Chatwoot";
$apps[$x]['description']['he-il'] = "Chatwoot";
$apps[$x]['description']['it-it'] = "Chatwoot";
$apps[$x]['description']['nl-nl'] = "Chatwoot";
$apps[$x]['description']['pl-pl'] = "Chatwoot";
$apps[$x]['description']['pt-br'] = "Chatwoot";
$apps[$x]['description']['pt-pt'] = "Chatwoot";
$apps[$x]['description']['ro-ro'] = "Chatwoot";
$apps[$x]['description']['ru-ru'] = "Chatwoot";
$apps[$x]['description']['sv-se'] = "Chatwoot";
$apps[$x]['description']['uk-ua'] = "Chatwoot";

//permission details
$y=0;
$apps[$x]['permissions'][$y]['name'] = "chatwoot_view";
$apps[$x]['permissions'][$y]['menu']['uuid'] = "5061da10-1c7f-4aab-8132-b50afa857a09";
$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
$apps[$x]['permissions'][$y]['groups'][] = "admin";
$y++;
$apps[$x]['permissions'][$y]['name'] = "chatwoot_edit";
$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
$apps[$x]['permissions'][$y]['groups'][] = "admin";
$y++;
$apps[$x]['permissions'][$y]['name'] = "chatwoot_admin";
$apps[$x]['permissions'][$y]['groups'][] = "superadmin";

//default settings
$y=0;
$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "d28b319b-f96c-4d2c-b0a0-b1a36b5defbb";
$apps[$x]['default_settings'][$y]['default_setting_category'] = "chat";
$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "chatwoot_url";
$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
$apps[$x]['default_settings'][$y]['default_setting_description'] = "Base URL for chatwoot API calls";
$y++;
$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "0632e7ec-3940-4b6a-b89e-83935cbcbbbd";
$apps[$x]['default_settings'][$y]['default_setting_category'] = "chat";
$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "platform_access_token";
$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
$apps[$x]['default_settings'][$y]['default_setting_description'] = "Chatwoot Platform Access Token";

//schema details
$y=0;
$apps[$x]['db'][$y]['table']['name'] = "v_chatwoot_platform_user";
$apps[$x]['db'][$y]['table']['parent'] = "";
$z=0;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "chatwoot_platform_user_uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "user_id";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "int";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "access_token";
$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_date";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_user";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_date";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_user";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

$y++;
$apps[$x]['db'][$y]['table']['name'] = "v_chatwoot_account";
$apps[$x]['db'][$y]['table']['parent'] = "";
$z=0;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "chatwoot_account_uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "account_id";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "int";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "domain_uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "foreign";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = "v_domains";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = "domain_uuid";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_date";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_user";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_date";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_user";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

$y++;
$apps[$x]['db'][$y]['table']['name'] = "v_chatwoot_user";
$apps[$x]['db'][$y]['table']['parent'] = "v_chatwoot_account";
$z=0;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "chatwoot_user_uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "user_id";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "int";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "account_id";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "integer";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "int";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "foreign";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = "v_chatwoot_account";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = "account_id";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;		
$apps[$x]['db'][$y]['fields'][$z]['name'] = "access_token";
$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "pubsub_token";
$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "user_uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "foreign";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = "v_users";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = "user_uuid";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "domain_uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "foreign";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = "v_domains";
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = "domain_uuid";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_date";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_user";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_date";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
$z++;
$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_user";
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";