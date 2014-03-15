<?php
class Post {
	private $_database,
			$_data,
			$_exists;
	
	public function __construct($id = null) {
		$this->_database = Database::getInstance();
		
		if($id) {
			$data = $this->_database->get('Post', array('id', '=', $id));
			
			if($data->count()) {
				$this->_data = $data->first();
				$this->_exists = true;
			}
		}
	}
	
	public function update($id, $fields = array()) {
		
		if(!$this->_database->update('Post', $id, $fields)) {
			throw new Exception('There was a problem updating a post.');
		}
	}
	
	public function create($fields) {
		if(!$this->_database->insert('Post', $fields)) {
			throw new Exception('There was a problem creating a post.');
		}
	}
	
	public function data() {
		return $this->_data;
	}
	
	public function exists() {
		return $this->_exists;
	}
}
?>