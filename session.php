<?php
class Session {

	// if session exists
	public static function exists($name) {
		return (isset($_SESSION[$name])) ? true : false;
	}

	// returns session name
	public static function put($name, $value) {
		return $_SESSION[$name] = $value;
	}

	public static function get($name) {
		return $_SESSION[$name];
	}

	// deletes session
	public static function delete($name) {
		if(self::exists($name)) {
			unset ($_SESSION[$name]); 
		}
	}

	// if session exists returns message which will show oh the screen and then deletes so next time user refreshes the page message wont appear.
	public static function flash($name, $string = '') {
		if(self::exists($name)) {
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}
	}
}