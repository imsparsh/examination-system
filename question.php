<?php require('includes/config.php'); 

if(!$user->is_logged_in()){ header('Location: index.php'); }
    
       		$tttmt = $db->prepare('SELECT membersession FROM members WHERE username = :username');
		$tttmt->execute(array(':username' => $_SESSION['userid']));
		$rowkt = $tttmt->fetch(PDO::FETCH_ASSOC);
                
        if( $_SESSION['sess'] === $rowkt['membersession'] )
        {


if(isset($_SESSION['userid']) && isset($_SESSION['id']))
{

        if(isset($_POST['idstart']) || isset($_POST['settled']))
            
            {
            
	$stmtr = $db->query('select * from questions');
        
                $correct=0;
                $incorrect=0;
                $unmarked=0;   
       while( $rowr = $stmtr->fetch(PDO::FETCH_ASSOC))
            {
                $i=$rowr['qid'];
                $ans=$rowr['cans'];
                if(isset($_POST['n_'.$i]))
                {
                    if($_POST['n_'.$i]===$ans){$correct++;}
                    else    {
                        $incorrect++;
                    }
                }
                elseif (!isset($_POST['n_'.$i])) {
                $unmarked++;
                }     
            }//while closed.....
                $total=$correct+$incorrect+$unmarked;            
                $attempted=$total-$unmarked;
                $perc=($correct/$total)*100;
                if($perc >= 40)
                {
                $status = 'PASS';
                }
                else
                {
                    $status = 'FAIL';
                }

                try {
			$utmt = $db->prepare("UPDATE result SET status = :status, correct = :correct, incorrect = :incorr, unmarked = :unmarked, percentage = :perc WHERE memberID = :memberID");
			$utmt->execute(array(
                                ':memberID' => $_SESSION['idno'],
				':status' => $status,
				':correct' => $correct,
				':incorr' => $incorrect,
				':unmarked' => $unmarked,
				':perc' => $perc
			));     

			//redirect to index page
                         unset($_POST['idstart']);
			header('Location: calculation.php');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}                
                
    }
	
$day=date('d')+00;     
$hr=date('H')+00; 
$min=date('i')+00;
$sec=date('s')+00;
$add1=0;$add2=0;$add3=0;
if($sec>=60)
{
$add1=$sec/60;
$sec=$sec%60;
$min+=$add1;
}
if($min>=60)
{
$add2=$min/60;
$min=$min%60;
$hr+=$add2;
}
if($hr>=24)
{
$add3=$hr/24;
$hr=$hr%60;
$day+=$add3;
}



?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />        
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript" src="jquery.countdownTimer.js"></script>
        <script src="disableclick.js"></script>        
        <script src="anyslider.js"></script>        
        <link rel="stylesheet" type="text/css" href="css/jquery.countdownTimer.css" />        
        <link rel="stylesheet" type="text/css" href="css/anyslider.css" />        
        <title>HitSpar Techno | Questions</title>
        <script>
        function gotostart()
        {
            window.document.getElementById("gostart").height=35;
            window.document.getElementById("gostart").width=35;
            document.forms[0].submit();            
            return true;
        }
        </script>        
	<script type="text/javascript">
	  $(function(){
	    $('#future_date').countdowntimer({
//              startDate : "<?php echo date('Y/m/d H:i:s'); ?>", 
//                dateAndTime : "<?php echo date('Y/m/').$day.(' ').$hr.(':').$min.(':').$sec; ?>",
//		           startDate : "2014/11/13 06:00:00", 
//                dateAndTime : "2014/11/13 09:00:00",
                hours: '1',
				size : "lg",
              borderColor : "#7cbcd6",
              fontColor : "rgba(26,89,120,0.9)",
              backgroundColor : "#FFFFFF",
              timeSeparator : ":",
              tickInterval : 1,
              timeUp : timeOutSubmit
	    });
	  });
        window.moveTo(0,0);
        window.resizeTo(screen.width,screen.height);
        
    document.onkeydown = function() {
    window.location='logout.php';
    window.close();
    };
    
          function timeOutSubmit()
          {
    //  window.location="/examination/calculation.php";
            document.forms[0].submit();
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
</head>
    <body background="images/bg.jpg">
        <div style="float: left"> 
        <p><b>Current Time:</b> <span id="servertime"></span></p>
<?php

        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<b>Your IP</b>:&nbsp;".$ip."<br>\n";

?></div>
        <div id="user" style="float: right;top: 0">Welcome <code><?php echo $_SESSION['userid'];?></code></div>        
        
        <div class="container">
            	<div id="countdowntimer">
                    <span id="future_date">
                        <span>
                            </div>
            <br>   <header>                
                <h1><span style="font-variant: small-caps; letter-spacing: 5px; font-weight: 700">&nbsp;&nbsp;HitSpar Techno</span>&nbsp; | Questionaire </h1>
            </header>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">            
            <div class="myslider">			
                    <?php
                    
		$stmt = $db->query('SELECT * FROM questions ORDER BY RAND()');
                $numrows = $stmt->rowCount();
                    $_SESSION['ques']=0;
       while( $rowqw = $stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>                                
                <div>
            <div class="questions" id="q_<?php echo $rowqw['qid'];?>">
            <div style="min-height: 100px; font-size: 2pc; padding: 10px 20px; opacity: .9;background: white">    
        <div style="min-height: 80px; font-size: 2pc; padding: 10px 20px; opacity: .9;background: white"  id="q_<?php echo $rowqw['qid'];?>">    
                        <?php echo ++$_SESSION['ques'].". ".$rowqw['question']." <br> "; ?>
        </div>
        <div style="min-height:100px; font-size: 1.5pc; padding: 20px 30px; opacity: .9;background: white;">  
        <input type="radio" id="q_<?php echo $rowqw['qid'];?>" name="n_<?php echo $rowqw['qid'];?>" value="1"> <?php echo $rowqw['ans1'];?> <br>
        <input type="radio" id="q_<?php echo $rowqw['qid'];?>" name="n_<?php echo $rowqw['qid'];?>" value="2"> <?php echo $rowqw['ans2'];?> <br>
        <input type="radio" id="q_<?php echo $rowqw['qid'];?>" name="n_<?php echo $rowqw['qid'];?>" value="3"> <?php echo $rowqw['ans3'];?> <br>
        <input type="radio" id="q_<?php echo $rowqw['qid'];?>" name="n_<?php echo $rowqw['qid'];?>" value="4"> <?php echo $rowqw['ans4'];?> <br>
        </div>
               </div>  </div>
             </div>  <?php } ?>
        </div>
            <div>          
                <input type="button" id='prev' value='&Leftarrow; &nbsp;&nbsp; &nbsp;&nbsp;Previous! &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;' name='prevquestion' class='butt' style="float:left"/>                
                <input type="button" id='next' value=' &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;Next!  &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&Rightarrow;' name='nextquestion' class='butt' style="float:right"/>                
            </div><br>
            <br><br>
            <br><br>
            <div class="exambutton">
                <button onclick="gotostart();" name="idstart" id="idstart">  <img src="images/loader.gif" height="0" width="0" id="gostart">&nbsp;&nbsp;&nbsp;Submit Exam</button>
				<input type=hidden name=settled value=go>
            </div>
                </form>
        </div><div>
    <script src="zepto.min.js"></script>
    <script src="anyslider.js"></script>
        </div>
        <script>
            $(function(){
        $('.myslider').anyslider({
            interval: 0,
            keyboard: false,
            speed: 1000
        });
    });
        </script>
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
        <script>
                window.moveTo(0,0);
        window.resizeTo(screen.width,screen.height);

        </script>
    </head>
    <body background="images/bg.jpg">
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
