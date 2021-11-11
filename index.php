<?php
  // Initialize sessions
  session_start();

  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
  }

  // Include config file
  require_once "config/config.php";

  // Define variables and initialize with empty values
  $username = $password = '';
  $username_err = $password_err = '';

  // Process submitted form data
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if username is empty
    if(empty(trim($_POST['username']))){
      $username_err = 'Please enter username.';
    } else{
      $username = trim($_POST['username']);
    }

    // Check if password is empty
    if(empty(trim($_POST['password']))){
      $password_err = 'Please enter your password.';
    } else{
      $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
      // Prepare a select statement
      $sql = 'SELECT id, username, password FROM users WHERE username = ?';

      if ($stmt = $mysql_db->prepare($sql)) {

        // Set parmater
        $param_username = $username;

        // Bind param to statement
        $stmt->bind_param('s', $param_username);

        // Attempt to execute
        if ($stmt->execute()) {

          // Store result
          $stmt->store_result();

          // Check if username exists. Verify user exists then verify
          if ($stmt->num_rows == 1) {
            // Bind result into variables
            $stmt->bind_result($id, $username, $hashed_password);

            if ($stmt->fetch()) {
              if (password_verify($password, $hashed_password)) {

                // Start a new session
                session_start();

                // Store data in sessions
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;

                // Redirect to user to page
                header('location: welcome.php');
              } else {
                // Display an error for passord mismatch
                $password_err = 'Invalid password';
              }
            }
          } else {
            $username_err = "Username does not exists.";
          }
        } else {
          echo "Oops! Something went wrong please try again";
        }
        // Close statement
        $stmt->close();
      }

      // Close connection
      $mysql_db->close();
    }
  }
  
  // Include config file
	require_once 'config/config.php';


	// Define variables and initialize with empty values
	$username = $password = $confirm_password = "";

	$username_err = $password_err = $confirm_password_err = "";

	// Process submitted form data
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Check if username is empty
		if (empty(trim($_POST['username']))) {
			$username_err = "Please enter a username.";

			// Check if username already exist
		} else {

			// Prepare a select statement
			$sql = 'SELECT id FROM users WHERE username = ?';

			if ($stmt = $mysql_db->prepare($sql)) {
				// Set parmater
				$param_username = trim($_POST['username']);

				// Bind param variable to prepares statement
				$stmt->bind_param('s', $param_username);

				// Attempt to execute statement
				if ($stmt->execute()) {
					
					// Store executed result
					$stmt->store_result();

					if ($stmt->num_rows == 1) {
						$username_err = 'This username is already taken.';
					} else {
						$username = trim($_POST['username']);
					}
				} else {
					echo "Oops! ${$username}, something went wrong. Please try again later.";
				}

				// Close statement
				$stmt->close();
			} else {

				// Close db connction
				$mysql_db->close();
			}
		}

		// Validate password
	    if(empty(trim($_POST["password"]))){
	        $password_err = "Please enter a password.";     
	    } elseif(strlen(trim($_POST["password"])) < 6){
	        $password_err = "Password must have atleast 6 characters.";
	    } else{
	        $password = trim($_POST["password"]);
	    }
    
	    // Validate confirm password
	    if(empty(trim($_POST["confirm_password"]))){
	        $confirm_password_err = "Please confirm password.";     
	    } else{
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($password_err) && ($password != $confirm_password)){
	            $confirm_password_err = "Password did not match.";
	        }
	    }

	    // Check input error before inserting into database

	    if (empty($username_err) && empty($password_err) && empty($confirm_err)) {

	    	// Prepare insert statement
			$sql = 'INSERT INTO users (username, password) VALUES (?,?)';

			if ($stmt = $mysql_db->prepare($sql)) {

				// Set parmater
				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Created a password

				// Bind param variable to prepares statement
				$stmt->bind_param('ss', $param_username, $param_password);

				// Attempt to execute
				if ($stmt->execute()) {
					// Redirect to login page
					header('location: ./login.php');
					// echo "Will  redirect to login page";
				} else {
					echo "Something went wrong. Try signing in again.";
				}

				// Close statement
				$stmt->close();	
			}

			// Close connection
			$mysql_db->close();
	    }
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="assets/images/favicon.png" rel="icon" type="image/png">

    <!-- Basic Page Needs
        ================================================== -->
    <title>Purplelite</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Purplelite is - an authentication system built by the purple team on side hustle internship 4.0">

    <!-- icons
    ================================================== -->
    <link rel="stylesheet" href="assets/css/icons.css">

    <!-- CSS 
    ================================================== --> 
    <link rel="stylesheet" href="assets/css/uikit.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://unpkg.com/tailwindcss%402.2.19/dist/tailwind.min.css" rel="stylesheet"> 

    <style>
        body{
            background-color: #f0f2f5;
        } 
        
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
        }
    </style>

</head> 
<body>
   

    <div class="lg:flex max-w-5xl min-h-screen mx-auto p-6 py-10">
        <div class="flex flex-col items-center lg: lg:flex-row lg:space-x-10">

            <div class="lg:mb-12 flex-1 lg:text-left text-center">
                <img src="assets/images/logo.png" alt="" class="lg:mx-0 lg:w-52 mx-auto w-40">
                <p class="font-medium lg:mx-0 md:text-2xl mt-6 mx-auto sm:w-3/4 text-xl"> Connect with friends and the world around you on Purplelite.</p>
            </div>
            <div class="lg:mt-0 lg:w-96 md:w-1/2 sm:w-2/3 mt-10 w-full">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="p-6 space-y-4 relative bg-white shadow-lg rounded-lg">
                        
                        <div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
                         
                            <input type="text" name="username" id="username" placeholder="Username" class="with-border" value="<?php echo $username ?>">
              <span class="w-full" style="color:#F90000;"><?php echo $username_err;?></span>
            </div>
                                                   
                        <div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
                         
                            <input type="password" name="password" id="password" placeholder="Password" class="with-border" value="<?php echo $password ?>">
              <span class="help-block" style="color:#F90000;"><?php echo $password_err;?></span>                           
                        </div>

                        <div>
                        	<button type="submit" class="bg-blue-600 font-semibold p-2 mt-5 rounded-md text-center text-white w-full">   Log In
                                    </button>
                                      </div>
                    <a href="password_reset.php" class="text-blue-500 text-center block"> Forgot Password? </a>
                    <hr class="pb-3.5">
                    <div class="flex">
                        <a href="#register" type="button" class="bg-green-600 hover:bg-green-500 hover:text-white font-semibold py-3 px-5 rounded-md text-center text-white mx-auto" uk-toggle>
                            Create New Account
                        </a>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm"> <a href="#" class="font-semibold hover:underline"> Create a Page </a> for a celebrity, band or business </div>
            </div>
    
        </div>
    </div>
  
    <!-- This is the modal -->
    <div id="register" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-xl shadow-2xl p-0 lg:w-5/12">
            <button class="uk-modal-close-default p-3 bg-gray-100 rounded-full m-3" type="button" uk-close></button>
            <div class="border-b px-7 py-5">
                <div class="lg:text-2xl text-xl font-semibold mb-1"> Sign Up</div>
                <div class="text-base text-gray-600"> It’s quick and easy.</div>
            </div>
            	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="lg:p-10 p-6 space-y-3 relative bg-white shadow-xl rounded-md">
        	                                                                    
                        	<div class="grid lg:grid-cols-2 gap-5">
                        	
                            	<div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
                                
                                <input type="text" name="username" id="username" class="with-border" placeholder="Username" value="<?php echo $username ?>">
        			<span class="help-block" style="color:#F90000;"><?php echo $username_err;?></span>
        		</div> 
                        </div>                              
                        
                       <input type="email" placeholder="Info@example.com" class="with-border">                                              
                                                                        
                        <div class="grid lg:grid-cols-2 gap-3">
                    <div>
                        <label class="mb-0"> Gender </label>
                        <select class="selectpicker mt-2 with-border">
                            <option>Male</option>
                            <option>Female</option>
                        </select>

                    </div>
                    </div>
                                     
                                     <div>
                        <div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
                            
                            <input type="password" name="password" id="password" placeholder="******" class="with-border" value="<?php echo $password ?>">
        			<span class="help-block" style="color:#F90000;"><?php echo $password_err; ?></span>                           
                        </div>
                        </div>
                        
                        <div>
                        <div class="form-group <?php (!empty($confirm_password_err))?'has_error':'';?>">
                        	
        			<input type="password" name="confirm_password" id="confirm_password" placeholder="******" class="with-border" value="<?php echo $confirm_password; ?>">
        			<span class="help-block" style="color:#F90000;"><?php echo $confirm_password_err;?></span>
        		</div>
        </div>
                                                                          
                   <p class="text-xs text-gray-400 pt-3">By clicking Sign Up, you agree to our
                    <a href="#" class="text-blue-500">Terms</a>, 
                    <a href="#" class="text-blue-500">Data Policy</a> and 
                    <a href="#" class="text-blue-500">Cookies Policy</a>. 
                     You may receive SMS Notifications from us and can opt out any time.
                </p>
                           
                           <div class="flex">
        			<button type="submit" class="bg-blue-600 font-semibold mx-auto px-10 py-3 rounded-md text-center text-white"> Get Started </button>      
        </div>                                                   
                    </form>

        </div>
    </div>

    <!-- For Night mode -->
    <script>
        (function (window, document, undefined) {
            'use strict';
            if (!('localStorage' in window)) return;
            var nightMode = localStorage.getItem('gmtNightMode');
            if (nightMode) {
                document.documentElement.className += ' night-mode';
            }
        })(window, document);
    
        (function (window, document, undefined) {
    
            'use strict';
    
            // Feature test
            if (!('localStorage' in window)) return;
    
            // Get our newly insert toggle
            var nightMode = document.querySelector('#night-mode');
            if (!nightMode) return;
    
            // When clicked, toggle night mode on or off
            nightMode.addEventListener('click', function (event) {
                event.preventDefault();
                document.documentElement.classList.toggle('dark');
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('gmtNightMode', true);
                    return;
                }
                localStorage.removeItem('gmtNightMode');
            }, false);
    
        })(window, document);
    </script>
  
    <!-- Javascript
    ================================================== -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/tippy.all.min.js"></script>
    <script src="assets/js/uikit.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/bootstrap-select.min.js"></script>
    <script src="https://unpkg.com/ionicons%405.2.3/dist/ionicons.js"></script>

</body>
</html>