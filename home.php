<?php require('includes/config.php'); 
if(!$user->is_logged_in()){ header('Location: index.php'); }

       		$tttmt = $db->prepare('SELECT membersession FROM members WHERE username = :username');
		$tttmt->execute(array(':username' => $_SESSION['userid']));
		$rowkt = $tttmt->fetch(PDO::FETCH_ASSOC);
                
        if( $_SESSION['sess'] === $rowkt['membersession'] )
        {

if(isset($_SESSION['userid']) && isset($_SESSION['id']))
{
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <script src="disableclick.js"></script>        
        <script>
        //disable keyboard on the webpage...            
        document.onkeydown = new Function("return false");

        window.moveTo(0,0);
        sx=screen.availWidth ;
        sy=screen.availHeight ;
        window.resizeTo(sx,sy) ;

</script>
<script>
    
        function gotostart()
        {
            window.document.getElementById("gostart").height=35;
            window.document.getElementById("gostart").width=35;
            setTimeout('Redirect()', 100);
            return true;
        }
        function Redirect() 
        { window.location="start.php"; }
        
        
</script>
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
        <title>HitSpar Techno</title>
    </head>
    <body background="images/bg.jpg" style="min-width: 1300px;">
        <div style="float: left"> 
        <p><b>Current Time:</b> <span id="servertime"></span></p>
<?php
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<b>Your IP</b>:&nbsp;".$ip."<br>\n" ;        
?></div>
        <div id="user" style="float: right;top: 0">Welcome <code><?php echo $_SESSION['userid'];?></code> | <a href="logout.php"> Logout </a> </div>        
        <div class="container">
            <br>   <header><h1>Welcome to <span style="font-variant: small-caps; letter-spacing: 5px; font-weight: 700">&nbsp;&nbsp;HitSpar Techno</span>&nbsp;&nbsp; Certification Exam</h1></header>  
            <div class="home" style='font-size:1.5pc; height:300px'>
			<p style='margin: 30px 50px'>			
			<br>
							Welcome, We are HitSpar Technologies, leading techno-innovators in the various sectors of the computing world.We at HitSpar Technologies, excel at what we do and the prime motive of working is to make a better world for you, keeping your wallet in consideration.<br><br>
				HitSpar Techno Certification (HSTC) is an initiative to bring out efficient and brilliant software enthusiasts from the crowd of thousands of graduates and non-graduates in the field of computer technology. The certification provided by us is a mark of genuine talent and is respected throughout the world by all the companies when they recruit people. So make sure you get one.<br><br>
				Best Of Luck for your exam.
			</p>

            </div> 
            <div class="exambutton">
            <button value="Go To Exam" onclick="gotostart();"> <img src="images/loader.gif" height="0" width="0"  id="gostart">&nbsp;&nbsp; Go To Exam</button>
            </div>
        </div>
    </body>
</html>
<?php 
}
 else { ?>
<meta http-equiv="refresh" content="0,index.php">    
<?php
}

        }
        
 else 
     {
     
       
          if(isset($_POST['logother']))
     {
                         $_SESSION['sess'] = crypt(date('r'));
       		$uptmt = $db->prepare('UPDATE members SET membersession  = :sess WHERE username = :username');
		$uptmt->execute(array(
                    ':username' => ($_SESSION['userid']),
                    ':sess' => $_SESSION['sess']
                        ));
                    $user->logout(); 

                    unset($_SESSION['userid']);
                    unset($_SESSION['id']);
                    unset($_SESSION['start']);
                    unset($_SESSION['idno']);
                    unset($_SESSION['sess']);
                
              
			header('Location: reset.php');
			exit;
        
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
    </head>
    <body background="images/bg.jpg" style="min-width: 1000px;">
        <div class="container">
                    <div id="user">Welcome <?php echo $_SESSION['userid'];?></div>        
            <br>
            <header>
                <h1> <span>HitSpar Techno</span> Certification</h1>
            </header>
            <br><br>
            <div class="home" style="font-family: sans-serif; font-size: 2pc; letter-spacing: .15em; margin: auto 100px">
                <br>
                Sorry, we do not allow multiple logins.<br><br>
                Someone logged in here using your credentials. If it was you, click on <code> Close</code>.<br>
                Otherwise click on <code>Log me out of other devices</code> to terminate every session created and you will be redirected to 'Reset Password' page to recover your account.<br>
                <div class="sessionoutbutton" style="margin: 20px 20px">
                <div class="btn-group">
                        <input type="button" class='btn-large' value="Close" onclick="window.location = 'index.php';">
                </div>
                    <div class="btn-group">
                        <form method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="thisform">
                            <input type="hidden" value="hellyeah"  id="logother" name='logother'>
                            <input type="button" value='Log me out of other devices' class='btn-large' onclick="document.getElementById('thisform').submit();"/>      
                        </form>
                </div>
            </div>
            </div> 

        </div>          
        </div>
     </body>
</html>
<?php     
     }
?>
