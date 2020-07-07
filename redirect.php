<?php
// if location has been define redirect
class Redirect {
	public static function to($location = null) {
		if($location) {
			header('Location:' . $location);
			exit();
		}
	}
}