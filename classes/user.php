<?php
require_once 'core/init.php';

class User {
	private $_db,
		    $_data,
		    $_sessionName,
		    $_isLoggedIn;


	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');

		if(!$user) {
			// is a user logged in
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} 
			}
		} else {
			$this->find($user);
		}

	}

	public function create($table, $fields = array()) {
		if(!$this->_db->insert($table, $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($email = null, $password = null) {
		$user = $this->find($email);

		if($user) {
			if($this->data()->password === Hash::make($password, $this->data()->salt)) {
				Session::put($this->_sessionName, $this->data()->id);
				return true;
			}
		}

		return false;
	}

	 // destroys session when user click 'logout'
	 public function logout() {
	 		session_destroy();
	 		Session::delete($this->_sessionName);
	  }

	 // access for example current logged in users id
	public function data() {
		return $this->_data;
	}

	// access informaton if user is logged_in
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

}