<?php require('includes/config.php'); 

       		$tttmt = $db->prepare('SELECT membersession FROM members WHERE username = :username');
		$tttmt->execute(array(':username' => $_SESSION['userid']));
		$rowkt = $tttmt->fetch(PDO::FETCH_ASSOC);
                
        if( $_SESSION['sess'] == $rowkt['membersession'] )
        {

if(isset($_SESSION['userid']) && isset($_SESSION['id']))
{
    unset($_SESSION['idstart']);
unset($_POST['settled']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <script href="jquery.js"></script>
        <script src="disableclick.js"></script>        
        <script>
        function gotostart()
        {
            window.document.getElementById("gostart").height=38;
            window.document.getElementById("gostart").width=38;
            setTimeout('Redirect()', 1000);

        function RedirectHOME() 
        { window.location="index.php"; }

        
        window.open("question.php",'_blank','directories=no,location=no,menubar=no,resizable=no,\n\
        toolbar=no,scrollbars=no,addressbar=no,width=screen.width,height=screen.height');

        RedirectHOME();
        self.close();
            return true;
    }
    function removeDisabled()
    {
   document.getElementById("startcheck").checked?
   document.getElementById("checkoff").removeAttribute("disabled"):
   document.getElementById("checkoff").setAttribute("disabled","disabled");
        return true;
    }
        
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
        <title>HitSpar Techno | Start Your Exam</title>
    </head>
    <body background="images/bg.jpg" onkeypress="return DisabledKeys();" style="min-width: 1000px;">
        <div style="float: left"> 
        <p><b>Current Time:</b> <span id="servertime"></span></p>
<?php
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<b>Your IP</b>:&nbsp;".$ip."<br>\n" ;        
?></div>
        <div id="user" style="float: right;top: 0">Welcome <code><?php echo $_SESSION['userid'];?></code> | 
		<a href="logout.php"> Logout </a> </div>        
        <div class="container">
            <br>            <br>   <header><h1 style="font-variant: small-caps; letter-spacing: 4px; 
                               font-weight: 900; background: #7cbcd6; margin:1px auto;
                               width: 400px;height: 65px;padding: 1px auto;padding-top: 25px;
                               margin-top: -25px">Start Your Exam</h1>
                </header> 
            <div class="home" style='font-size:1.5pc;text-align:left'>
<p style='margin: 40px 50px'>			<br>
			I. Duration<br>
The test will be of 1 hrs duration.<br>
All the testees are advised to divide their time between different modules efficiently.<br><br>

II. Modules<br>
The test contains 5 modules, each module having 5 questions each.<br>
Modules are: mySql, C, Javascript, PHP and HTML.<br><br>

III. Questions<br>
There is only one correct response for each question out of four responses given, or otherwise stated in the question.<br>
There is no negative marking.<br><br>

IV. Regulatory Instructions<br>
No candidate is allowed to carry any textual material, printed or written, bits of papers, pager, mobile phone, any electronic device, etc., except the Admit Card, identity proof and pen inside the examination hall/room. <br>
Do not press any key while attempting the questionaire.<br>
Violation of this will lead to cancellation of candidature.<br>

All calculations/writing work are to be done in the rough sheet provided only and on completion of the test candidates must hand over the rough sheets to the invigilator on duty in the Room/Hall.<br><br>

Every Testee must abide with the code of conduct of HitSpar techno exam.<br>
			
			
	</p>		
			<br>
            </div> 
                <div class="check" style="display: block"> <input type="checkbox" id="startcheck" onclick="removeDisabled();"> &nbsp; I agree to above Terms and Conditions. </div>
            <div class="exambutton">
            <button value="Go To Exam" onclick="gotostart();" id="checkoff" disabled> <img src="images/loader.gif" height="0" width="0"  id="gostart">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Start Exam &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
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
 else {
     
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
    <body background="images/bg.jpg"  style="min-width: 1000px;">
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
