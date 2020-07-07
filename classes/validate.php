<?php
class Validate {
	private $_passed = false,
	$_errors = array(),
	$_db = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}
	

	// defines data which will be looped trough and array of rules and rule values
	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {
				$value = trim($source[$item]);
				$item = escape($item);
			
				// validation rules
				if($rule === 'required' && empty($value)) {
					$this->addError("{$item} is required");
				} else if(!empty($value)){
					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be less than {$rule_value} characters.");
							}
						break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} already exists");
							}
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

	// ads error to errors array
	private function addError($error) {
		$this->_errors[] = $error;
	}

	// return list of errors
	public function errors() {
		return $this->_errors;
	}

	// if validation passed
	public function passed() {
		return $this->_passed;
	}
}

