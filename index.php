<?php require('includes/config.php'); 
$_SESSION['start']='start';

if(isset($_POST['submitlogin'])){

	$username = $_POST['username'];
	$password = $_POST['password'];

	$pattern="/[A-Z0-9]/";
	if(preg_match($pattern,$_POST['username'])===1)
	{	$errorin[] = 'User not found.';}
	
	else if($user->login($username,$password)){ 
            
       		$ktmt = $db->prepare('SELECT memberID FROM members WHERE username = :username');
		$ktmt->execute(array(':username' => $username));
		$rowk = $ktmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['idno'] = $rowk['memberID'];
                $_SESSION['sess'] = crypt(date('r'));
       		$uptmt = $db->prepare('UPDATE members SET membersession  = :sess WHERE username = :username');
		$uptmt->execute(array(
                    ':username' => $username,
                    ':sess' => $_SESSION['sess']
                        ));
                unset($_POST['logother']);
                
		?>

<!--<script>

var wnd, URL;  //global variables

 //specifying "_blank" in window.open() is SUPPOSED to keep the new page from replacing the existing page
 wnd = window.open("home.php", "_blank"); //get reference to just-opened page
 //if the "general rule" above is true, a new tab should have been opened.
 
   wnd.focus();             //when browser not set to automatically show newly-opened page, this MAY work
 
    window.open("home.php",'_blank','directories=no,location=no,menubar=no,resizable=no,\n\
        toolbar=no,addressbar=no,scrollbars=yes,width=1000,height=500,fullscreen=yes');
        window.self.close();
    </script>-->

<meta http-equiv="refresh" content="0,home.php">

<?php 
exit;
	
	} else {
		$errorin[] = 'Wrong username or password or your account has not been activated yet.';
	}

}//end if submit


//if form has been submitted process it
if(isset($_POST['submit'])){

	//very basic validation
	
	$pattern="/[A-Z0-9]/";
	if(preg_match($pattern,$_POST['usernamesignup'])===1)
	{	$errorup[] = 'Only lowercase letters are allowed for username.';}
	else if(strlen($_POST['usernamesignup']) < 6){
		$errorup[] = 'Username is too short.';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['usernamesignup']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($row['username']==$_POST['usernamesignup']){
			$errorup[] = 'Username provided is already in use. Please enter any other username.';
		}
			
	}

	if(strlen($_POST['passwordsignup']) < 6){
		$errorup[] = 'Password is too short, it must be atleast 6 characters long.';
	}

	if(strlen($_POST['passwordsignup_confirm']) < 6){
		$errorup[] = 'Confirm password is too short.';
	}

	if($_POST['passwordsignup'] != $_POST['passwordsignup_confirm']){
		$errorup[] = 'Passwords mismatched. Enter Again.';
	}

	//email validation
	if(!filter_var($_POST['emailsignup'], FILTER_VALIDATE_EMAIL)){
	    $errorup[] = 'Please enter a valid email address.';
	} else {
		$etmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$etmt->execute(array(':email' => $_POST['emailsignup']));
		$rowe = $etmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($rowe['emailsignup'])){
			$errorup[] = 'Email provided is already in use. Choose any another email address.';
		}
			
	}


	//if no errors have been created carry on
	if(!isset($errorup)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['passwordsignup'], PASSWORD_BCRYPT);

		//create the activation code
		$activation = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active) VALUES (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $_POST['usernamesignup'],
				':password' => $hashedpassword,
				':email' => $_POST['emailsignup'],
				':active' => $activation
			));
			$id = $db->lastInsertId('memberID');                        
			$rtmt = $db->prepare('INSERT INTO result (memberID,status,correct,incorrect,unmarked,percentage) VALUES (:memberID, :status, :correct, :incorr, :unmarked, :perc)');
			$rtmt->execute(array(
				':memberID' => $id,
				':status' => 'NONE',
				':correct' => 0,
				':incorr' => 0,
				':unmarked' => 0,
				':perc' => 0
			));                        

			//send email
			$to = $_POST['emailsignup'];
			$subject = "Registration Confirmation | HitSpar";
			$body = "Thank you for registering at HitSpar Techno. \n\n To activate your account, follow the link:\n\n ".DIR."activate.php?x=$id&y=$activation\n\n Regards, \n Site Admin, \n Hit\$par";
                        $headers=array(
                            'From: <'.SITEEMAIL.'> \r\n',
                            'Content-Type:text/html',
                            'Reply-To: <'.SITEEMAIL.'>\r\n'
                        );
			mail($to, $subject, $body, implode("/r/n",$headers));

			//redirect to index page
			header('Location: index.php?action=joined#toregister');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $errorup[] = $e->getMessage();
		}

	}

}

?>

<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>Online Examination Login and Registration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="author" content="Sparsh" />
        <link rel="shortcut icon" href="favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
	<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
        <script src="script.js"></script>
        <script>
//var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->'; //SSI method of getting server date
var currenttime = '<? print date("F d, Y H:i:s", time())?>'; //PHP // getting server date

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
var serverdate=new Date(currenttime);

function padlength(what){
var output=((what.toString().length===1)?"0"+what:what);
return output;
}

function disptime(){
serverdate.setSeconds(serverdate.getSeconds()+1);
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear();
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
document.getElementById("servertime").innerHTML=datestring+" | "+timestring;
}

window.onload=function(){
setInterval("disptime()", 999);
};            
        </script>
    </head>
    <body style="min-width: 1000px;">
        <p><b>Current Time:</b> <span id="servertime"></span></p>
<?php
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<b>Your IP</b>:&nbsp;".$ip."<br>\n" ;        
?>
        <div class="container">
            <header>
                <h1>Online Examination for <span style="font-variant: small-caps;">HitSpar Techno</span> Certification</h1>
            </header>
            <section>				
                <div id="container_demo" >
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <input type="button" style="display: none;visibility: hidden" name="openlink" id="openlink">
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" role="form" name="submitlogin" method="post" autocomplete="off"> 
                                <h1>Log in</h1> 
                                
                                
				<?php
				//check for any errors
				if(isset($errorin)){
					foreach($errorin as $errorin){ ?>
                                <p style="color: red;">	<?php	echo $errorin;?> </p>
                                <?php
					}
				}

				if(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 style='font-style:oblique;font-weight:600'>Your account has been activated. Log in now.</h2><br>";
							break;
						case 'reset':
							echo "<h2 style='font-style:oblique;font-weight:600'>Please check your inbox for a reset link.</h2><br>";
							break;
						case 'resetAccount':
							echo "<h2 style='font-style:oblique;font-weight:600'>Password changed successfully, you may now login.</h2><br>";
							break;
					}

				}

				?>
                                
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your Username </label>
                                    <input id="username" name="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($errorin)){ echo $_POST['username']; } ?>" tabindex="1" required="required" type="text" />
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your Password </label>
                                    <input id="password" name="password" required="required" type="password" class="form-control input-lg" placeholder="Password" tabindex="2" /> 
                                </p>
                                <p class="keeplogin">
                                    <a href='reset.php' target="_blank">Forgot your Password?</a>
                                </p>
                                <p class="login button"> 
                                    <input type="submit" value="Login" tabindex="5" id="submitlogin" name="submitlogin"/> 
								</p> 
                                <p class="change_link">
							Not a member yet ?
							<a href="#toregister" class="to_register">Register as a new User</a>
				</p>
                                <p class="change_link">
									Not a member yet ?
									<a href="#toregister" class="to_register">Register as a new User</a>
								</p>
                            </form>
                        </div>
                        <div id="register" class="animate form">
                            <form  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#toregister" autocomplete="on" name="signint" id="signin" onsubmit="return validatesign(document.signin);" method="post"> 
                                <h1> Register </h1> 
                                
       				<?php
				//check for any errors
				if(isset($errorup)){
					foreach($errorup as $errorup){
						echo '<p style="color: red;">'.$errorup.'</p>';
					}
				}
				//if action is joined, show success
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2  style='font-style:oblique;font-weight:600'> Congratulations!!. You have Registered successfully..  please check your email to activate your account.</h2>";
				}
				?>                                
                                <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Username</label>
                                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="User Name" class="form-control input-lg" value="<?php if(isset($errorup)){ echo $_POST['usernamesignup']; } ?>" tabindex="1" />
                                </p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" >Email Address</label>
                                    <input id="emailsignup" name="emailsignup" class="form-control input-lg" autocomplete="no" placeholder="Enter your Email" value="<?php if(isset($errorup)){ echo $_POST['emailsignup']; } ?>" tabindex="2" required="required" type="email" /> 
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Password </label>
                                    <input id="passwordsignup" name="passwordsignup" class="form-control input-lg" tabindex="3" required="required" type="password" value="" placeholder="eg. X8df!90EO"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Confirm Password </label>
                                    <input id="passwordsignup_confirm" name="passwordsignup_confirm" class="form-control input-lg" tabindex="4" required="required" value="" type="password" placeholder="eg. X8df!90EO"/>
                                </p>
                                <p class="signin button"> 
                                    <input type="submit" name="submit" value="Register" tabindex="5"/> 
								</p>
                                <p class="change_link">  
									Already a member ?
									<a href="#tologin" class="to_register"> Go and Log In </a>
								</p>
                            </form>
                        </div>
						
                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>
