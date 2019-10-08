<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
|-------------------------------------------------------------------------------
| Authentication Library Config
|-------------------------------------------------------------------------------
|
| 'auth_allowed_attempt'
|
|	The number of time a user can try loging in at a time. If a user submits
|       incorrect informations at most the value of this variable, the login
|       proccess will stop executing and set an error.
|
| 'auth_session_id'
|
|	Name of the session item that will store user ID.
|
| 'auth_session_permit'
|
|	Name of the session item that will store boolean value for login permission.
|
| 'auth_session_enkey'
|
|	Name of the session item that will store the encryted key for login validation.
|
| 'auth_cookie_id'
|
|	Name of the cookie item that will store user ID.
|
| 'auth_cookie_key'
|
|	Name of the cookie item that will store encrypted key.
|       It will be used to validate the cookie.
|
| 'auth_cookie_expiry'
|
|	Cookie expiration time in seconds. Value must be integer.
|       Current expiration time is 30 days. ( 60 x 60 x 24 x 30 = 2592000)
|
| 'auth_destroy_all'    
|
|	Whether to deleted all session data when loging out.
|       Options are: TRUE or FALSE (boolean)
|
| 'auth_validate_ip'    
|
|	Shoud the process validate IP address too.
|       Options are: TRUE or FALSE (boolean)
|
*/
$config['auth_allowed_attempt'] = 5;
$config['auth_session_id'] = 'chabi_auth_id';
$config['auth_session_enkey'] = 'chabi_auth_enkey';
$config['auth_cookie_id'] = 'chabi_auth_id';
$config['auth_cookie_key'] = 'chabi_auth_key';
$config['auth_cookie_expiry'] = 2592000;
$config['auth_destroy_all'] = FALSE;
$config['auth_validate_ip'] = FALSE;
