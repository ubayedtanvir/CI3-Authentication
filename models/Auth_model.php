<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Manages database activites for authentication library.
 * @author  Abdullah Ubayed Tanvir
 */
class Auth_model extends CI_Model
{
    /**
     * Name of the table where user data is stored.
     * 
     * @var    string
     */
    private $_table = 'users';

    /**
     * Name of the table where users login session data is stored.
     * 
     * @var    string
     */
    private $_session_table = 'auth_log';

    /**
     * Name of the table where users login attempts data is stored.
     * 
     * @var    string
     */
    private $_attempts_table = 'auth_attempts';

    /**
     * Primary field of the table.
     * 
     * @var    string
     */
    private $_primary = 'id';

    /**
     * Fetches user data by primary key.
     * 
     * @method  getByID
     * @param   int     User ID
     * @return  mixed
     */

    public function getByID($userid)
    {
        // set a variable to store user's info
        $user = NULL;

        // query for the user
        $query = $this->db->get_where($this->_table, array($this->_primary => $userid));

        // check if any row returned
        if ($query->num_rows() === 1) {
            // return the row
            $user = $query->row();
        }

        return $user;
    }

    /**
     * Fetches user data if found.
     * 
     * @method  getAuthUser
     * @param   int     User ID
     * @return  mixed
     */

    public function getAuthUser($userEmail)
    {
        // set a variable to store user's info
        $user = NULL;

        // query for the user
        $query = $this->db->get_where($this->_table, ['email_address' => $userEmail]);

        // check if any row returned
        if ($query->num_rows() === 1) {
            //  store users info in a variable
            $user = $query->row();
        }

        return $user;
    }

    /**
     * Adds a new row to the login_attempts table if a user tries to login
     * with invalid credentials.
     * 
     * @method  increase_login_attempt
     * @param   int     user ID
     * @return  bool
     */

    public function increase_login_attempt($id = NULL)
    {
        // set the user ID
        $this->db->set('user_id', $id);

        // set the IP address of the user
        $this->db->set('ip_address', $this->input->ip_address());

        // set the time of login attempt
        $this->db->set('time', 'NOW()', FALSE);

        // insert data and return the result
        return $this->db->insert($this->_attempts_table);
    }

    /**
     * Determines if a user attempted maximum nuber of invalid login.
     * 
     * @method  login_attempt_exceeded
     * @param   int     user ID
     * @return  bool
     */

    public function login_attempt_exceeded($userid = NULL)
    {
        // set where clauses if user ID was provided
        if ($userid !== NULL) {
            $this->db->where('user_id', $userid);
        }

        $this->db->where('ip_address', $this->input->ip_address());
        // whe need to get data for last fifteen munites
        $this->db->where('time > NOW() - INTERVAL 20 MINUTE');

        // query for data
        $query = $this->db->get($this->_attempts_table);

        // compare maximum allowed time with number of rows returned and return
        // the result.
        return ($this->config->item('auth_allowed_attempt') <= $query->num_rows());
    }

    /**
     * Clears all the rows of a user login attempt when the user submits
     * correct combination of username and password.
     * 
     * @method  clear_login_attempts
     * @param   int     user ID
     * @return  bool
     */

    public function clear_login_attempts($id)
    {
        // set where clauses
        $this->db->where('user_id', $id);
        // we will delete all the rows with a specific IP address
        $this->db->where('ip_address', $this->input->ip_address());

        // delete all the rows
        $this->db->delete($this->_attempts_table);

        // check if any rows affected and return the result.
        return ($this->db->affected_rows() > 0);
    }

    /**
     * Updates the users last login time in that users row.
     * 
     * @method  update_last_login
     * @param   object  Users object
     *          string  Long encrypted key
     *          bool    Whether to remember the session or not
     * @return  bool
     */

    public function update_last_login($user, $enkey, $remember)
    {
        // set column data
        $this->db->set('user_id', $user->id);
        $this->db->set('enc_key', $enkey);
        $this->db->set('browser', $this->agent->browser());
        $this->db->set('browser_version', $this->agent->version());
        $this->db->set('ip_address', $this->input->ip_address());
        $this->db->set('platform', $this->agent->platform());
        $this->db->set('authorized_at', 'NOW()', FALSE);

        if ($remember === 1) {
            $this->db->set('authorize_using', 'cookie');

            // calculate expiry date
            $expires_at = date("Y-m-d h:i:s", time() + $this->config->item('auth_cookie_expiry'));
            // set cookie expiry date for future validation
            $this->db->set('expires_at', $expires_at, TRUE);
        } else {
            $this->db->set('authorize_using', 'session');
        }

        // return if nothing gone right
        return $this->db->insert($this->_session_table);
    }

    /**
     * Validates authentication session data.
     * 
     * @method  check_session
     * @param   
     * @return  bool
     */

    public function check_session($id, $email, $enckey, $validate_using)
    {
        // query for the uesr
        $userQuery = $this->db->where(['id' => (int) $id, 'email_address' => $email])->get($this->_table);

        // check if any user exists
        if ($userQuery->num_rows() !== 1) {
            return FALSE;
        }

        // store user data
        $user = $userQuery->row();

        // check if the user is active
        if ((int) $user->status !== 1) {
            return FALSE;
        }

        // setup where clause array
        $where = array(
            'user_id' => $user->id,
            'enc_key' => $enckey,
            'browser' => $this->agent->browser(),
            'platform' => $this->agent->platform(),
            'status' => '1',
            'authorize_using' => $validate_using
        );

        // check whether to verify users IP address
        if ($this->config->item('auth_validate_ip') === TRUE) {
            // set another item in $where array for IP address validation
            $where['ip_address'] = $this->input->ip_address();
        }

        $sessionQuery = $this->db->where($where)->get($this->_session_table);

        // return if no results were found
        if ($sessionQuery->num_rows() !== 1){
            return FALSE;
        }

        // store the session data in a variable
        $sessionData = $sessionQuery->row();

        // check if the session should be expired
        if(!is_null($sessionData->expires_at) && expired($sessionData->expires_at)){
            // disable session from now on
            $this->disable_session($sessionData->user_id, $sessionData->enc_key);

            return FALSE;
        }

        // all set. The user has access to dashboard
        return TRUE;
    }

    /**
     * Changes authentication status.
     * 
     * @method  disable_session
     * @param   int     User ID
     *          string  Long encrypted key
     * @return  bool
     */

    public function disable_session($userID, $enckey)
    {
        // set where clause
        $this->db->where(['user_id' => $userID, 'enc_key' => $enckey]);

        // update users row and return the result
        return $this->db->update($this->_session_table, array('status' => '0'));
    }
}

/* End of file */