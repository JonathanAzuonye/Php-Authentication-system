<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: login.php');
    exit;
}
 
// Include config file
require_once 'config/config.php';
 
// Define variables and initialize with empty values
$new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';
 
// Processing form data when form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
 
    // Validate new password
    if(empty(trim($_POST['new_password']))){
        $new_password_err = 'Please enter the new password.';     
    } elseif(strlen(trim($_POST['new_password'])) < 6){
        $new_password_err = 'Password must have atleast 6 characters.';
    } else{
        $new_password = trim($_POST['new_password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST['confirm_password']))){
        $confirm_password_err = 'Please confirm the password.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = 'Password did not match.';
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        
        if($stmt = $mysql_db->prepare($sql)){
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_password, $param_id);
            
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Socialite Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Socialite is - Professional A unique and beautiful collection of UI elements">

   <!-- icons
    ================================================== -->
    <link rel="stylesheet" href="assets/css/icons.css">

    <!-- CSS 
    ================================================== --> 
    <link rel="stylesheet" href="assets/css/uikit.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="../../unpkg.com/tailwindcss%402.2.19/dist/tailwind.min.css" rel="stylesheet"> 


</head> 
<body>




    <div id="wrapper">

        <!-- Header -->
        <header>
            <div class="header_wrap">
                <div class="header_inner mcontainer">
                    <div class="left_side">

                        

                        <div id="logo">
                            <a href="feed.html">
                                <img src="assets/images/logo.png" alt="">
                                <img src="assets/images/logo-mobile.png" class="logo_mobile" alt="">
                            </a>
                        </div>
                    </div>

                  

                    <div class="right_side">

                        <div class="header_widgets">
                            <a href="pages-upgrade.html" class="is_link">  Upgrade </a>
                            <a href="#" class="is_icon" uk-tooltip="title: Cart">
                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="../../external.html?link=http://www.w3.org/2000/svg"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                            </a>
                            <div uk-drop="mode: click" class="header_dropdown dropdown_cart">
                            	    <div  class="dropdown_scrollbar" data-simplebar>

                            
                            </div>
                            <a href="#" class="see-all">Features Coming Up Soon..</a>
                            </div>

                            <a href="#" class="is_icon" uk-tooltip="title: Notifications">
                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="../../external.html?link=http://www.w3.org/2000/svg"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>                          
                            </a>
                            <div uk-drop="mode: click" class="header_dropdown">
                                 <div  class="dropdown_scrollbar" data-simplebar>
                                     
                                 </div>
                                 <a href="#" class="see-all">Features Coming Up Soon..</a>
                            </div>

                            <!-- Message -->
                            <a href="#" class="is_icon" uk-tooltip="title: Message">
                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="../../external.html?link=http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path></svg>                        
                            </a>
                            <div uk-drop="mode: click" class="header_dropdown is_message">
                                <div  class="dropdown_scrollbar" data-simplebar>
                                
                                </div>
                                <a href="#" class="see-all">Features Coming Up Soon..</a>
                            </div>


                            <a href="#">
                                <img src="assets/images/avatars/avatar-2.jpg" class="is_avatar" alt="">
                            </a>
                            <div uk-drop="mode: click;offset:5" class="header_dropdown profile_dropdown">

                                <a href="timeline.html" class="user">
                                    <div class="user_avatar">
                                        <img src="assets/images/avatars/avatar-2.jpg" alt="">
                                    </div>
                                    <div class="user_name">
                                        <div> Intern <?php echo $_SESSION['username']; ?> </div>
                                        <span> @<?php echo $_SESSION['username']; ?></span>
                                    </div>
                                </a>
                                <hr>
                                <a href="donate.php" class="is-link">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="../../external.html?link=http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                                    Donate To Purplelite  </span>
                                </a>
                                <hr>
                                <a href="password_reset.php">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="../../external.html?link=http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                                    Reset Password
                                </a>
                                
                                <a href="#" id="night-mode" class="btn-night-mode">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                      </svg>
                                     Night mode
                                    <span class="btn-night-mode-switch">
                                        <span class="uk-switch-button"></span>
                                    </span>
                                </a>
                                <a href="logout.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Log Out
                                </a>


                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </header>

        

        <!-- Main Contents -->
        <div class="main_content">
            <div class="mcontainer">
            
                <div class="bg-white lg:divide-x lg:flex lg:shadow-md rounded-md shadow lg:rounded-xl overflow-hidden lg:m-0 -mx-4">
                    
                    <div class="lg:w-2/3">

                        <div class="lg:flex lg:flex-col justify-between lg:h-full">

                            <!-- form header -->
                            <div class="lg:px-10 lg:py-8 p-6">
                                <h3 class="font-bold mb-2 text-xl">Reset Password</h3>
                                <p class=""> Please fill out this form to reset your password. </p>
                            </div>

                            <!-- form body -->
                            <div class="lg:py-8 lg:px-20 flex-1 space-y-4 p-6">
                            	
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 

                                <div class="line">
                                    <input class="line__input" id="password" autocomplete="off" name="password" type="text" onkeyup="this.setAttribute('value', this.value);" value="">
                                    <span for="username" class="line__placeholder"> Old Password  </span>
                                </div>
                                </br>                                
                                <div class="line">
                                	<div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">                                    
                                    	<input type="password" name="new_password" onkeyup="this.setAttribute('value', this.value);" class="line__input" id="password" autocomplete="off" value="<?php echo $new_password; ?>">
                                    <span for="username" class="line__placeholder"> New Password </span>
                                    <span class="help-block" style="color:#F90000;"><?php echo $new_password_err; ?></span>
                </div>
                                </div>
                                <div class="line">
                                	  <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">                                    
                                    	   <input type="password" name="confirm_password" class="line__input" id="password" autocomplete="off" onkeyup="this.setAttribute('value', this.value);">
                                    <span for="username" class="line__placeholder"> Confirm Password </span>
                                      <span class="help-block" style="color:#F90000;"><?php echo $confirm_password_err; ?></span>
                </div>
                                </div>                                

                            </div>

                            <div class="bg-gray-10 p-6 pt-0 flex justify-end space-x-3">
                                <button class="p-2 px-4 rounded bg-gray-50 text-red-500" href="welcome.php"> Cancel </button>
                                <button type="submit" class="button bg-blue-700"> Save </button>
                            </div>


                        </div>

                        <div class="bg-white rounded-md lg:shadow-md shadow" hidden>

                            <div class="grid grid-cols-2 gap-3 lg:p-6 p-4">
                                <div>
                                    <label for=""> Old Password</label>
                                    <input type="text" placeholder="" class="shadow-none with-border">
                                </div>
                                </br>
                                <div>
                                    <label for=""> New Password</label>
                                    <input type="text" placeholder="" class="shadow-none with-border">
                                 </div>
                                 <div class="col-span-2">
                                     <label for=""> Comfirm Password</label>
                                     <input type="text" placeholder="" class="shadow-none with-border">
                                 </div>
                                 
                              
                            </div>

                            <div class="bg-gray-10 p-6 pt-0 flex justify-end space-x-3">
                                <button class="p-2 px-4 rounded bg-gray-50 text-red-500" href="welcome.php"> Cancel </button>
                                <button type="submit" class="button bg-blue-700"> Save </button>
                            </div>

                        </div>

                        <br>

                        <div class="bg-white rounded-md lg:shadow-md shadow" hidden>
                             <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4> Who can follow me ?</h4>
                                        <div> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, </div>
                                    </div>
                                    <div class="switches-list -mt-8 is-large">
                                        <div class="switch-container">
                                            <label class="switch"><input type="checkbox" checked><span class="switch-button"></span> </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4> Show my activities  </h4>
                                        <div> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, </div>
                                    </div>
                                    <div class="switches-list -mt-8 is-large">
                                        <div class="switch-container">
                                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4> Search engines </h4>
                                        <div> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, </div>
                                    </div>
                                    <div class="switches-list -mt-8 is-large">
                                        <div class="switch-container">
                                            <label class="switch"><input type="checkbox" checked><span class="switch-button"></span> </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4> Allow Commenting </h4>
                                        <div> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, </div>
                                    </div>
                                    <div class="switches-list -mt-8 is-large">
                                        <div class="switch-container">
                                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>




            </div>
        </div>

    </div>



    

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
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/tippy.all.min.js"></script>
    <script src="assets/js/uikit.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/bootstrap-select.min.js"></script>
    <script src="../../unpkg.com/ionicons%405.2.3/dist/ionicons.js"></script>

</body>
</html>
