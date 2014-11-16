<?php require('includes/config.php'); 

if(!isset($_REQUEST['key']))
{$_REQUEST['key']='NULL';}

$stmt = $db->prepare('SELECT resetToken, resetComplete FROM members WHERE resetToken = :token');
$stmt->execute(array(':token' => $_REQUEST['key']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$key = $_REQUEST['key'];
//if no token from db then kill the page
if(empty($row['resetToken'])){
	$stop = 'Invalid token provided, please use the link provided in the reset email.<br><br>';
} elseif($row['resetComplete'] == 'Yes') {
	$stop = 'Your password has already been changed!';
}

//if form has been submitted process it
if(isset($_POST['submitresP'])){

	//basic validation
	if(strlen($_POST['password']) < 6){
		$error[] = 'Password is too short.';
	}


	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		try {

			$stmt = $db->prepare("UPDATE members SET password = :hashedpassword, resetComplete = 'Yes', resetToken = ''  WHERE resetToken = :token");
			$stmt->execute(array(
				':hashedpassword' => $hashedpassword,
				':token' => $row['resetToken']
			));

			//redirect to index page
			header('Location: index.php?action=resetAccount');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
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
        <p><b>Current Time:</b> <span id="servertime"></span></p>
<?php
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<b>Your IP</b>:&nbsp;".$ip."<br>\n" ;        
?>
        <div class="container">
            <br>   <header><h1><span style="font-variant: small-caps; letter-spacing: 5px; font-weight: 700">&nbsp;&nbsp;HitSpar Techno </span>| Reset Password</h1></header>  <br>
            <br>
            <div class="smallhome" style="width: 900px; min-height: 400px ; padding: 0 auto">
                 <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?key=<?php echo $key; ?>" autocomplete="off" style="min-height:330px">

                     <div class="form-group">

	    	<?php if(isset($stop)){

	    		echo "<p style='color:red'>$stop</p>";

	    	}
                else
                    { 
			//check for any errors
					if(isset($error)){
						foreach($error as $error){
							echo '<p style="color:red">'.$error.'</p>';
						}
                                                echo '<br>';
					}

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2  style='font-style:oblique;font-weight:600'>Your account is now active you may now log in.<br></h2><br>";
							break;
						case 'reset':
							echo "<h2  style='font-style:oblique;font-weight:600'>Please check your inbox for a reset link.<br></h2><br>";
							break;
					}
					?>
                        
							<div class="form-group">
						Enter new Password: &nbsp;
                                                                                             <input style="margin: 30px;
                                                    font-family: cursive;font-size: 1.2pc;text-align: center;
                                                    box-shadow: 0 1px 1px black;width:300px; padding:10px"  type="password" name="password" id="password" class="form-control input-lg" placeholder="Enter Password" value="" tabindex="1"><br>     
                                                
                                                        </div>
							<div class="form-group">
						Confirm new Password:	
                                                                          <input style="margin: 30px;
                                                    font-family: cursive;font-size: 1.2pc;text-align: center;
                                                    box-shadow: 0 1px 1px black;width:300px; padding:10px" type="password" id="passwordConfirm" name="passwordConfirm" class="form-control input-lg" placeholder="Confirm password" value="" tabindex="2"><br>
                                                        
                                                        </div>
				   	<div class="exambutton">
                                    <input class="button" style="width: 350px"  type="submit" id="submitresP" name="submitresP" value="Click to reset Password" tabindex="3">
				</div>
                     </div>
				</form>

			<?php } ?>
                                 <p style="padding: 10px auto; padding-bottom: 10px;"><a href='index.php' style="background-color: rgba(255,255,255,.3)"><span style="margin: 25px 12px;font-size: 1.3pc">`Back to Login page.</span></a></p>
		</div>
	</div>

    </body>
</html>
