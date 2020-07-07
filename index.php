<?php
require_once 'core/init.php';

date_default_timezone_set('Europe/Riga');

// if session exist calls 'flash' function whichs shows that user has been successfuly registered and after refresh message will dissapear
if(Session::exists('home')) {
	echo '<p class="application_message">' . Session::flash('home') . '</p>';
}

// checks which forms submit btn was submitted and does input exist, if yes performs input field validation
if(Input::exists() && isset($_POST["submit_registration"])) {
	$validate = new Validate();
	// definition of rules for validation
	$validation = $validate->check($_POST, array(
		'name' => array(
			'required' => true,
			'min' => 3,
			'max' => 50,
			'unique' => 'users'
		),
		'email' => array(
			'required' => true,
			'unique' => 'users'
		),
		'password' => array(
			'required' => true,
			'min' => 8
		)

	));
// if all fields inserted and there is no errors saves data to database 
	if($validation->passed()) {
		$user = new User();
		$salt = Hash::salt(32);

		try {

			$user->create('users', array(
				'name' => Input::get('name'),
				'email' => Input::get('email'),
				'password' => Hash::make(Input::get('password'), $salt),
				'salt' => $salt,
				'joined' => date('Y-m-d H:i:s')  
			));

			// calls 'flash' functions message
			Session::flash('home', 'You have been registered and now can login.');
			Redirect::to('index.php');

		} catch(Exception $e) {
			die($e->getMessage());
		}
	} else {
		// if there has been incorrect values on form submit then calls errors from error function
		foreach($validation->errors() as $error) {
		?> <div class="application_message">
			<?php
		 echo $error, '<br>';  ?>
		</div>
		<?php 

			
		}
	}
} else if(Input::exists() && isset($_POST["submit_login"])) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'email' => array('required' => true),
				'password' => array('required' => true)
			));

			if($validation->passed()) {
				$user = new User();

				$login = $user->login(Input::get('email'), Input::get('password'));

				if($login) {
					Redirect::to('update.php');
				} else {
					?>
					<p class="application_message">Login failed, please insert correct data.</p>;
					<?php
				}

			} else {
				foreach($validation->errors() as $error) {
					?>
				<div class="application_message">
				<?php	echo $error, '<br>'; ?>
				</div>
				<?php
				}
	    	}
	 } 
?>
<!doctype html>
<html>   
<head>   
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie-edge"></meta>
	<title></title>
<link href="css/style.css" type="text/css" rel="stylesheet">

</head>
<body>

	<div class="container" id="container">
		<div class="form-container sign-up-container left">
		    <form action="" method="POST" id="registration_form">
		        <h1 class="element">Sign Up</h1>
		        <img id="imgLogo" src="img/logo.jpg">
		        <div class="underline element"> </div>
		        <div class="left">
		        <input type="text" class="name" name="name" placeholder="Name" value="<?php echo escape(input::get('name'))?>"> <div style="color:#f44368;" id="star_signup_name" class="star">*</div>
		        <input type="email" class="email" name="email" placeholder="Email" value="<?php echo escape(input::get('email'))?>"> <div style="color:#f44368;" id="star_signup_email" class="star">*</div>
		        <input type="password" class="password" name="password" placeholder="Password" value="">
		   		 </div> <div style="color:#f44368;" id="star_signup_pass" class="star">*</div>
		        <button id="submit" class="element" type="submit" name="submit_registration" value="signUp">SIGN UP</button>
    		</form>
		</div> 

		<?php include("login.php") ?> 

		<div class="overlay-container box">
		  <div class="overlay">
		  	<div class="overlay-panel overlay-right col-1">
	            <h1>Don't have an account?</h1>
	            <div class="underline"> </div>
	            <p>Lorem ipsum dolor sit amet,
					consectetur adipisicing elit, sed do
					eiusmod tempor incididunt ut labore et
					dolore magna aliqua.</p>
	            <button class="ghost" id="signUp">SIGN UP</button>
	        </div>
	        <div class="overlay-panel overlay-left col-2">
	           <div>
	            <h1>Have an account?</h1>
	            <div class="underline"> </div>
	            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
	            <button class="ghost" id="signIn">LOGIN</button>
	          </div>
	        </div>
          </div>
		</div>
	</div>

	<footer>
		
	</footer>

<script type="text/javascript" src="js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
</body>
</html>
