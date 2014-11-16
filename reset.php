<?php require('includes/config.php'); 

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: home.php'); } 

//if form has been submitted process it
if(isset($_POST['submitres'])){

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $errorres[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['email'])){
			$errorres[] = 'Email provided is not recognised.';
		}
			
	}

	//if no errors have been created carry on
	if(!isset($errorres)){

		//create the activasion code
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $db->prepare("UPDATE members SET password = :password, resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
                                ':password' => NULL,
				':email' => $row['email'],
				':token' => $token
			));

			//send email
			$to = $row['email'];
			$subject = "Password Reset";
			$body = "Someone from your account requested to reset the password. \n\nIf this wasn't you, just ignore this.\n\nTo reset your password, visit the address: ".DIR."resetPassword.php?key=$token";
                        $headers=array(
                            'From: <'.SITEEMAIL.'> \r\n',
                            'Content-Type:text/html',
                            'Reply-To: <'.SITEEMAIL.'>\r\n'
                        );
                        mail($to, $subject, $body,  implode("/r/n",$headers));

			//redirect to index page
			header('Location: index.php?action=reset');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $errorres[] = $e->getMessage();
		}

	}

}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <title>HitSpar Techno</title>
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
    <body background="images/bg.jpg">
        <div style="float: left"> 
        <p><b>Current Time:</b> <span id="servertime"></span></p>
<?php
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<b>Your IP</b>:&nbsp;".$ip."<br>\n" ;        
?></div>
        <div class="container">
            <br>   <header><h1><span style="font-variant: small-caps; letter-spacing: 5px; font-weight: 700">&nbsp;&nbsp;HitSpar Techno </span>| Forgot Password</h1></header>  <br>
            <br>
             <div class="smallhome">
                 <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="reset" autocomplete="off" style="height:290px">
                     			<div class="form-group">
                            <?php
				//check for any errors
				if(isset($errorres)){
					foreach($errorres as $errorres){
						echo '<p style="color:red">'.$errorres.'</p>';
					}
				}

				elseif(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 style='font-style:oblique;font-weight:600'>Your account is now active you may now log in.</h2>";
							break;
						case 'reset':
							echo "<h2 style='font-style:oblique;font-weight:600'>Please check your inbox for a reset link.</h2>";
							break;
					}
                                        echo '<br>';
				}
                        else {     ?>
                                        Enter the Registered Email Address <br>     
                        <?php }
				?>

                                             <input style="margin: 30px;
                                                    font-family: cursive;font-size: 1.2pc;text-align: center;border-radius: 5px;
                                                    box-shadow: 0 1px 1px black;width:450px; padding:10px" type="email" name="email" id="email" class="form-control input-lg" placeholder="Enter the mail address" value="" tabindex="1" required="required"><br>
				</div>
				
		
				<div class="exambutton">
                                    <input class="button" style="width: 320px" type="submit" name="submitres" value="Send Reset Link" tabindex="2">
				</div>
			</form>                            
                 <p><a href='index.php' style="background-color: rgba(255,255,255,.3)"><span style="margin: 25px 12px;font-size: 1.3pc">`Back to Login page.</span></a></p>
            </div> 

        </div>

    </body>
</html>
