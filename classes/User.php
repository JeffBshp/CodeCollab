<?php
class User {
	private $_database,
			$_data,
			$_sessionName,
			$_exists,
			$_isLoggedIn;
	
	public function __construct($user = null) {
		$this->_database = Database::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		
		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->logout();
				}
			}
		} else {
			$this->find($user);
		}
	}
	
	public function update($fields = array(), $id = null) {
		
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		
		if(!$this->_database->update('User', $id, $fields)) {
			throw new Exception('There was a problem updating an account.');
		}
	}
	
	public function create($fields) {
		if(!$this->_database->insert('User', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}
	
	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_database->get('User', array($field, '=', $user));
			
			if($data->count()) {
				$this->_data = $data->first();
				$this->_exists = true;
				return true;
			}
		}
		return false;
	}
	
	public function login($username = null, $password = null) {
		$user = $this->find($username);
		
		if($user) {
			if($this->data()->pass_hash === Hash::make($password, $this->data()->salt)) {
				Session::put($this->_sessionName, $this->data()->id);
				return true;
			}
		}
		return false;
	}
	
	public function logout() {
		Session::delete($this->_sessionName);
	}
	
	public function data() {
		return $this->_data;
	}
	
	public function exists() {
		return $this->_exists;
	}
	
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
}
?>