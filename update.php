<?php
require_once 'core/init.php';

if(Session::exists('success')) {
	// if session exist calls 'flash' function whichs shows that loged in user has submited form
	echo '<p class="application_message">' . Session::flash('success') . '</p>';
}

// checks is form submited btn and does input exist, if yes performs input field validation
// if fields are not empty saves data to database 
if(Input::exists() && isset($_POST["submit_profile"])) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'current_city' => array('required' => true),
		'home_town' => array('required' => true),
		'school' => array('required' => true),
		'age' => array('required' => true)
	));

	if($validation->passed()) {
		$user = new User();

		try {

			$user->create('attributes', array(
				'user id' => $user->data()->id,
				'current city' => Input::get('current_city'),
				'home town' => Input::get('home_town'),
				'school' => Input::get('school'),
				'age' => Input::get('age')
			));

			Session::flash('success', 'Thank you! Your data has been submitted.');
			Redirect::to('update.php');

		} catch(Exception $e) {
			die($e->getMessage());
		}
	} else {
		foreach($validation->errors() as $error) {
		?> <div class="application_message">
			<?php
		 echo $error, '<br>';  ?>
		</div>
		<?php 

			
		}
	}
}



// if session does not exist directs user to index page
if(!Session::exists(Config::get('session/session_name'))){
    header('location: index.php');
}



 $user = new User(); // 
if($user->isLoggedIn()) {
	// if user is logged in shows message
?>
	<p class="application_message">Hello, you are logged in <?php echo escape($user->data()->name); ?>!</p>
	<?php	




?>



	<ul style="list-style: none;"> <?php // if log out is clicked by a user logout page is assigned to a href, logout function will be called, session destroyed and user will be redirected to index page  ?>
		<li class="top_corner"><a href="logout.php">Log out</a></li>
	</ul>
<?php 
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


		    <form action="" class="profile" method="POST" id="registration_form">
		        <h1 class="element">User data</h1>
		        <img id="imgLogo" src="img/logo.jpg">
		        <input type="" id="current_city" name="current_city" placeholder="Current city" value="<?php echo escape(input::get('current_city'))?>">
		        <input type="" id="home_town" name="home_town" placeholder="Home town" value="<?php echo escape(input::get('home_town'))?>">
		        <input type="" id="school" name="school" placeholder="School" value="<?php echo escape(input::get('school'))?>">
		        <input type="number" id="age" name="age" placeholder="Age" value="<?php echo escape(input::get('age'))?>">
		        <button id="submitprofile" type="submit" name="submit_profile" value="submit">SUBMIT</button>
    		</form>

    		<footer>
				
			</footer>



<script type="text/javascript" src="js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
</body>
</html>
