<?php
class Hash {

	// makes a hash, adds randomly generated string to a password
	public static function make($string, $salt = '') {
		return hash('sha256', $string . $salt);
	}

	// generate salt
	public static function salt($length) {
		// mcrypt_create_iv provides a lot of random characters
		return mcrypt_create_iv($length);
	}
}