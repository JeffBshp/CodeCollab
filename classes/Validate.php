<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_database = null;
			
	public function __construct() {
		$this->_database = Database::getInstance();
	}
	
	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {
			
				$value = trim($source[$item]);
				$item = escape($item);
				
				if($rule === 'required' && empty($value)) {
					$this->addError("{$item} is required");
				} else if(!empty($value)) {
					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be at least {$rule_value} characters");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be no more than {$rule_value} characters");
							}
						break;
						case 'match':
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}");
							}
						break;
						case 'unique':
							$check = $this->_database->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} already exists");
							}
						break;
						case 'non-numeric':
							if(is_numeric($value) || is_numeric($value[0])) {
								$this->addError("{$item} may not be a number and may not start with a number");
							}
						break;
						default:
						break;
					}
				}
			}
		}
		
		if(empty($this->_errors)) {
			$this->_passed = true;
		}
		
		return $this;
	}
	
	private function addError($error) {
		$this->_errors[] = $error;
	}
	
	public function errors() {
		return $this->_errors;
	}
	
	public function passed() {
		return $this->_passed;
	}
}
?>