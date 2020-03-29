<?php
/***********************************************************************************
-- Account_login controller class verifies if the user inputs abide by the ---------
-- set of rules and checks if the user's account is blocked or not. ---------------- 
------------------------------------------------------------------------------------
-- Author: Irene Gayle Roque -------------------------------------------------------
***********************************************************************************/
class Account_login extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		
		$data['title'] = 'Account Login';
		//let account_login page use $title
		$this->load->view('account_login', $data);
	}

	//verify() --> validates if inputs abide by the set of rules
	public function verify(){
		
		$this->form_validation->set_rules('txtuser', 'Username', 'required');
		$this->form_validation->set_rules('txtpass', 'Password', 'required|callback_check_user');

		if($this->form_validation->run() == TRUE){
            //if inputs abide by the set of rules, display 'Success'. If not, go back to account_login page
			echo 'Success';

		}else{
			$this->index();
		}
	}

    //check_user() --> method that will check if user's account is blocked
	public function check_user(){
		
		$username = $this->input->post('txtuser');
		$password = $this->input->post('txtpass');

		$this->load->model('accounts_login_model');
		//pass values of $username and $password as parameters of login method in accounts_login_model
		$login = $this->accounts_login_model->login($username, $password);

		if($login){
			//if login method of accounts_login_model loaded successfully, return 'true'
			return true;
		}else{
			//if login method of accounts_login_model didn't load successfully, proceed to nested if-else statements
			if(isset($_SESSION['error_count'][$username])){
				///if an error has occured, add one error corresponded to username
				$_SESSION['error_count'][$username]+=1;
			}else{
				//if there are no more errors occured then there is only one error corresponding to username
				$_SESSION['error_count'][$username] = 1;
			}
            
			$isBlocked = $this->accounts_login_model->isBlocked($username);

			if($isBlocked){
				//if isBlocked of accounts_login_model loaded successfully, display a message saying, 'Account is temporarily blocked. '
				$this->form_validation->set_message('check_user', 'Account is temporarily blocked. ');
			}else if(isset($_SESSION['error_count'][$username])){
				$this->accounts_login_model->block($username);
				//displays '3 consecutive failed login attempts. Account Blocked' if there are 3 error counts
				$this->form_validation->set_message('check_user', '3 consecutive failed login attempts. Account Blocked');
			}else{
				//displays 'Invalid Username/password' if there is only one error count
				$this->form_validation->set_message('check_user', 'Invalid Username/password');

			}

			return false;
		}


	}

	//unblock_login() --> method that will load the unblock_login page
	public function unblock_login(){
        $data['prompt'] = 'Enter username and password to unblock your account';
        //let's the unblock_login page use $prompt
		$this->load->view('unblock_login', $data);

	}

	//unblock_verify() --> method that will validate if inputs from unblock_login page abide by the set of rules
	public function unblock_verify(){

		$this->form_validation->set_rules('txtuser', 'Username', 'required');
		$this->form_validation->set_rules('txtpass', 'Password', 'required|callback_check_user');

		if($this->form_validation->run() == TRUE){
            //if inputs abide by the set of rules:
            //set 'txtuser' to $username
            $username = $this->input->post('txtuser');
            //load unblock method of accounts_login_model with $username as parameter
			$this->accounts_login_model->unblock($username);

		}else{
			//if inputs didn't abide by set of rules, go back to unblock_login page
			$this->unblock_login();
		}
	}



}
?>
