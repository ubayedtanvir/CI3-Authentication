# CodeIgniter 3 Authentication Library
A simple and secure authentication library built for CodeIgniter 3 by [Abdullah Ubayed Tanvir](https://www.linkedin.com/in/ubayedtanvir).
This library is built only for authentication, not for registration, password recovery session or email verification. For registration purpose, please use PHP's built in function `password_hash()` for hashing users password as this library uses `password_verify()` function to verify users password. For more information, please check [this link](https://www.php.net/manual/en/function.password-hash.php).

# Server requirements
This library needs CodeIgniter version 3 and PHP version >= 5.6. This library may also work in PHP version 5.4 but strongly encourage you to upgrade.

# Database tables
Please check **database_tables.sql** file for table structures. Three tables are required in total. You can rename table names if you want but do not change column names. Also ensure that you have changed table names in **Auth model** located in models directory.
* Change **_table** property value for users table name
```bash
private $_table = 'users';
```
* Change **_session_table** property value for authentication logs data
```bash
private $_session_table = 'auth_log';
```
* Change **_attempts_table** property value for invalid login attempts data
```bash
private $_attempts_table = 'auth_attempts';
```

# Installation
* Download this repository as zip file.
* Unzip on your project **aplication** folder.
* Autoload `'auth'` library in your `application/config/autoload.php` file. Or just load the library using
```bash
$this->load->library('auth');
```
* Set `encryption_key` on `application/config/config.php` file as it is neccessary for encryption library of CodeIgniter.
* Now you can play with authentication.

# APIs
* **validate()**
  Validates authentication.
    * string $email_address - Email address of the user. (required)
    * string $users_password - Password of the users account. (required)
    * int $remember_session - Remember the session or not. (not required but the value must be 1)
* **is_logged()**
  Checks if any user is logged or not. Simply use `if($this->auth->is_logged())` to validate login session.
* **getUserData()**
  Fetches authorized users row from database.
    * array $columns - `users` tables column names to select. If empty, this method will return all the data.
* **getError()**
  Returns single messege if any error occurs while authenticating. Use `$this->auth->getError()` to fetch authentication error message.
* **getMessage()**
  Returns success message. Use `$this->auth->getMessage()` to fetch authentication success message.

# For reliable security
  * Enable `global_xss_filtering` in `application/config/config.php`
  * Use **POST** method when submitting the form.
  * Validate form data using `form_validation` library as this library does not validate form data.
  * You can optionaly use `$this->auth_model->login_attempt_exceeded()` to disable login page for a specific IP address.
  * Enable `auth_validate_ip` in `application/config/auth.php` file. This will validate IP address too while authenticating. Also note that, this config is disabled for a reason. If enabled and your users home/office IP address changes randomly, the user may automaticaly logged out.