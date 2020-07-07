<?php
require_once 'core/init.php';

// define new object
// call logout function 
$user = new User();
$user->logout();


Redirect::to('index.php');
