
<?php
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
<html lang="en" class="bg-gray-100">
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


 
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">

    <style>
        input , .bootstrap-select.btn-group button{
            background-color: #f3f4f6  !important;
            height: 44px  !important;
            box-shadow: none  !important; 
        }
        
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
        }
        
        .form-group s1pan {color: red;}
    </style>

</head>
<body class="bg-gray-100">


        <div id="wrapper" class="flex flex-col justify-between h-screen">
    
            <!-- header-->
            <div class="bg-white py-4 shadow dark:bg-gray-800">
                <div class="max-w-6xl mx-auto">
    
    
                    <div class="flex items-center lg:justify-between justify-around">
    
                        <a href="register.php">
                            <img src="assets/images/logo.png" alt="" class="w-32">
                        </a>
    
                        <div class="capitalize flex font-semibold hidden lg:block my-2 space-x-3 text-center text-sm">
                            <a href="login.php" class="py-3 px-4">Login</a>
                            <a href="register.php" class="bg-purple-500 purple-500 px-6 py-3 rounded-md shadow text-white">Register</a>
                        </div>
    
                    </div>
                </div>
            </div>
    
            <!-- Content-->
            <div>
                <div class="lg:p-12 max-w-xl lg:my-0 my-12 mx-auto p-6 space-y-">
                	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="lg:p-10 p-6 space-y-3 relative bg-white shadow-xl rounded-md">
        	                    
                        <h1 class="lg:text-2xl text-xl font-semibold mb-6"> Register </h1>

                        <div class="grid lg:grid-cols-2 gap-3">
                        	<div>
                            	<div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
                                <label class="mb-0" for="username"> Username </label>
                                <input type="text" name="username" id="username" class="bg-gray-100 h-12 mt-2 px-3 rounded-md w-full" value="<?php echo $username ?>">
        			<span class="help-block" style="color:#F90000;"><?php echo $username_err;?></span>
        		</div>       
                       </div>                          
                        
                        <div>
                            <label class="mb-0"> Email Address </label>
                            <input type="email" placeholder="Info@example.com" class="bg-gray-100 h-12 mt-2 px-3 rounded-md w-full">
                        </div>
                                                                        
                        <div class="grid lg:grid-cols-2 gap-3">
                            <div>
                                <label class="mb-0"> Gender </label>
                                    <div class="grid lg:grid-cols-2 gap-3">
                                <select class="selectpicker mt-2">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                                </div>

                            </div>
                                     </div>
                                     
                                     
                                     <div>
                        <div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
                            <label class="mb-0" for="password"> Password </label>
                            <input type="password" name="password" id="password" class="bg-gray-100 h-12 mt-2 px-3 rounded-md w-full" value="<?php echo $password ?>">
        			<span class="help-block" style="color:#F90000;"><?php echo $password_err; ?></span>                           
                        </div>
                        </div>
                        
                        <div>
                        <div class="form-group <?php (!empty($confirm_password_err))?'has_error':'';?>">
                        	<label for="confirm_password" class="mb-0">Confirm Password</label>
        			<input type="password" name="confirm_password" id="confirm_password" class="bg-gray-100 h-12 mt-2 px-3 rounded-md w-full" value="<?php echo $confirm_password; ?>">
        			<span class="help-block" style="color:#F90000;"><?php echo $confirm_password_err;?></span>
        		</div>
        </div>
                                                                          
                        <div class="checkbox">
                            <input type="checkbox" id="chekcbox1" checked="">
                            <label for="chekcbox1"><span class="checkbox-icon"></span> I agree to the <a href="pages-terms.html" target="_blank" class="uk-text-bold uk-text-small uk-link-reset"> Terms and Conditions </a>
                            </label>
                        </div>

                           <div>
                           <div class="form-grou">
        			<button type="submit" class="bg-blue-600 font-semibold p-2 mt-5 rounded-md text-center text-white w-full"> Create Account </button>      		        		</div>
        </div>                          
                        
                        <p>Already have an account? <a href="login.php" style="color:#2563EB;">Login here</a>.</p>
                    </form>


                </div>
            </div>
            
            <!-- Footer -->
    
            <div class="lg:mb-5 py-3 uk-link-reset">
                <div class="flex flex-col items-center justify-between lg:flex-row max-w-6xl mx-auto lg:space-y-0 space-y-3">
                    <div class="flex space-x-2 text-gray-700 uppercase">
                        <a href="#"> About</a>
                        <a href="#"> Help</a>
                        <a href="#"> Terms</a>
                        <a href="#"> Privacy</a>
                    </div>
                    <p class="capitalize"> © Copyright 2021 by <a href="https://facebook.com/jonathan.azuonye.71/">Purple Team</a></p>
                </div>
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