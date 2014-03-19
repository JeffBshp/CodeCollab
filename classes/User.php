<?php
class User {
	private $_database,
			$_sessionName,
			$_exists,
			$_isLoggedIn,
			$_id,
			$_username,
			$_firstName,
			$_lastName,
			$_email,
			$_hash,
			$_salt,
			$_registrationDate,
			$_profilePicture,
			$_about,
			$_visible;
	
	public function __construct($user = null) {
		$this->_database = Database::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		
		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				
				if($this->retrieveData($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->logout();
				}
			}
		} else {
			$this->retrieveData($user);
		}
	}
	
	public function update($fields = array(), $id = null) {
		
		if(!$id && $this->isLoggedIn()) {
			$id = $this->_id;
		}
		
		if(!$this->_database->update('User', $id, $fields)) {
			throw new Exception('There was a problem updating an account.');
		}
	}
	
	public function register($fields) {
		if(!$this->_database->insert('User', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}
	
	private function retrieveData($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_database->get('User', array($field, '=', $user));
			
			if($data->count()) {
				$this->_exists = true;
				$data = $data->first();
				$this->_id = $data->id;
				$this->_username = escape($data->username);
				$this->_firstName = escape($data->first_name);
				$this->_lastName = escape($data->last_name);
				$this->_email = escape($data->email);
				$this->_hash = $data->pass_hash;
				$this->_salt = $data->salt;
				$this->_registrationDate = $data->registration_date;
				$this->_profilePicture = $data->profile_picture;
				$this->_about = escape($data->about);
				$this->_visible = array(
					'name' => $data->name_visible,
					'email' => $data->email_visible,
					'about' => $data->about_visible,
					'posts' => $data->posts_visible);
				return true;
			}
		}
		return false;
	}
	
	public function login($username = null, $password = null) {
		$user = $this->retrieveData($username);
		
		if($user) {
			if($this->_hash === Hash::make($password, $this->_salt)) {
				Session::put($this->_sessionName, $this->_id);
				return true;
			}
		}
		return false;
	}
	
	public function checkPassword($password) {
		if($this->_hash === Hash::make($password, $this->_salt)) {
			return true;
		}
		return false;
	}
	
	public function logout() {
		Session::delete($this->_sessionName);
	}
	
	public function exists() {
		return $this->_exists;
	}
	
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	public function getUsername() {
		return $this->_username;
	}
	
	public function getFirstName() {
		return $this->_firstName;
	}
	
	public function getLastName() {
		return $this->_lastName;
	}
	
	public function getFullName() {
		return $this->_firstName . ' ' . $this->_lastName;
	}
	
	public function getEmail() {
		return $this->_email;
	}
	
	public function getRegistrationDate() {
		return $this->_registrationDate;
	}
	
	public function getProfilePicture() {
		return $this->_profilePicture;
	}
	
	public function getAbout() {
		return $this->_about;
	}
	
	public function getVisible() {
		return $this->_visible;
	}
}
?>