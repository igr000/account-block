<?php
/*****************************************************************************
-- Accounts_login_model communicates with database and contains methods ------
-- that will check if the account is blocked or not. ---=---------------------
------------------------------------------------------------------------------
-- Author: Irene Gayle Roque -------------------------------------------------
*****************************************************************************/
class accounts_login_model extends CI_model{
	
	public function __construct(){
		parent::__construct();
		//loads database
		$this->load->database();
	}
    
    //login()--> method that will check if the entered username and password corresponds to a existing account
	public function login($username, $password){ 
		$condition_array= array(
			'acc_username' => $username,
			'acc_password' => $password
		);
        //SELECT * FROM 'accounts' WHERE 'acc_username' = $username && 'acc_password' = $password
		$rs = $this->db->get_where('accounts', $condition_array);
		//count number of rows
		$row_count = $rs->num_rows();

		if($row_count > 0){
			//if row count is greater than 0, return a single row
			return $rs->row_array();
		}else{
			return FALSE;
		}
	}

    //isBlocked() --> method that will check if the user account corresponding to entered username and password is blocked
	public function isBlocked($username){
		$condition_array = array(
			'acc_username' => $username,
			'acc_isBlocked' => 1
		);
		//SELECT * FROM 'accounts' WHERE 'acc_username' = $username && 'acc_isBlocked' = 1
		$rs = $this->db->get_where('accounts', $condition_array);
		//count number of rows
		$row_count = $rs->num_rows();
        
		if($row_count > 0){
			//if row count is greater than 0, it means that the account corresponding to $username is blocked
			return true;
		}else{
			return FALSE;
		}
	}
    
    //block() --> method that will send email to the acc_email if the user account is blocked
	public function block($username){

		//initialize email
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $this->email->initialize($config);
        
        //use account_lookup method as $email
		$email = $this->account_lookup($username, 'acc_email');
        
        //sender
		$this->email->from('rollingsketches@gmail.com', 'Your Website');
		//recipient
		$this->email->to($email);
		//email subject
		$this->email->subject('Account Blocked');

		//load account_blocked page if message was sent
		$message = $this->load->view('account_blocked', null, TRUE);
		
		$this->email->message($message);
		$this->email->send();
        
        //SELECT 'acc_username' FROM 'accounts' WHERE 'acc_username' = $username
		$this->db->where('acc_username', $username);
		//change the selected account's 'acc_isBlocked' field from 0 to 1
		return $this->db->update('accounts', array('acc_isBlocked' => 1));
	}
    
    //account_lookup() --> method that will find the account
	public function account_lookup($username, $return){
		//SELECT * FROM 'accounts' WHERE 'acc_username' = $username
		$rs = $this->db->get_where('accounts', array('acc_username' => $username));
		$row = $rs->row();

		return $row->$return;

	}

    //unblock() -->
	public function unblock($username){
        
        //SELECT 'acc_username' FROM 'accounts' WHERE 'acc_username' = $username
		$this->db->where('acc_username', $username);
		//change the selected account's 'acc_isBlocked' field from 1 to 0
		$unblocked = $this->db->update('accounts', array('acc_isBlocked' => 0));

		if($unblocked){
			echo "Your account is now unblocked!";
		}else{
			echo "Failed to unblock your account";
		}
	}
}
?>