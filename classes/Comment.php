<?php
class Comment {
	private $_database,
			$_exists,
			$_id,
			$_postId,
			$_commenter,
			$_commentDate,
			$_content;
	
	public function __construct($id = null) {
		$this->_database = Database::getInstance();
		
		if($id) {
			$data = $this->_database->get('Comments', array('id', '=', $id));
			
			if($data->count()) {
				$this->_exists = true;
				$data = $data->first();
				$this->_id = $data->id;
				$this->_postId = $data->post_id;
				$this->_commenter = new User($data->user_id);
				$this->_commentDate = $data->comment_date;
				$this->_content = $data->content;
			}
		}
	}
	
	public function update($id, $fields = array()) {
		
		if(!$this->_database->update('Comments', $id, $fields)) {
			throw new Exception('There was a problem updating a comment.');
		}
	}
	
	public function create($fields) {
		if(!$this->_database->insert('Comments', $fields)) {
			throw new Exception('There was a problem creating a comment.');
		}
	}
	
	public function exists() {
		return $this->_exists;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	public function getPostId() {
		return $this->_postId;
	}
	
	public function getCommenter() {
		return $this->_commenter;
	}
	
	public function getCommentDate() {
		return $this->_commentDate;
	}
	
	public function getContent() {
		return $this->_content;
	}
}
?>