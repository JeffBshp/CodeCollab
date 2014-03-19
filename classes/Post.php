<?php
class Post {
	private $_database,
			$_exists,
			$_id,
			$_author,
			$_postDate,
			$_title,
			$_language,
			$_tags,
			$_promotions,
			$_content;
	
	public function __construct($id = null) {
		$this->_database = Database::getInstance();
		
		if($id) {
			$data = $this->_database->get('Post', array('id', '=', $id));
			
			if($data->count()) {
				$this->_exists = true;
				$data = $data->first();
				$language = $this->_database->get('Languages', array('id', '=', $data->language_id));
				if($language->count()) {
					$language = $language->first()->language_name;
				} else {
					$language = null;
				}
				$this->_id = $data->id;
				$this->_author = new User($data->user_id);
				$this->_postDate = $data->post_date;
				$this->_title = $data->title;
				$this->_language = $language;
				$this->_tags = $data->tags;
				$this->_promotions = $this->_database->action('SELECT COUNT(id) AS num', 'Promotion', array('post_id', '=', $data->id))->first()->num;
				$this->_content = $data->content;
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
	
	public function exists() {
		return $this->_exists;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	public function getAuthor() {
		return $this->_author;
	}
	
	public function getPostDate() {
		return $this->_postDate;
	}
	
	public function getTitle() {
		return $this->_title;
	}
	
	public function getLanguage() {
		return $this->_language;
	}
	
	public function getTags() {
		return $this->_tags;
	}
	
	public function getPromotions() {
		return $this->_promotions;
	}
	
	public function getContent() {
		return $this->_content;
	}
}
?>