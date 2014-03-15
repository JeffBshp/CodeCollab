<?php
class SearchResult {
	private $_result;
	private $_database = null;

	private function __construct() {
		$this->_database = Database::getInstance();
	}

	public static function generateResults($query) {
		return "ARRAY OF FORMATTED RESULTS";
	}

	private function formatResult($result) {
		return "FORMATTED RESULT";
	}
}
?>