function validate()
{
var x=document.forms["logint"]["username"].value;
var y=document.forms["logint"]["password"].value;
if (x=== null || x==="")
  {
  alert("Username must be filled out");
  return false;
  }
else if (y=== null || y==="")
  {
  alert("Please enter a Password");
  return false;
  }
  else return true;
}


function validatesign()
{
var x=document.forms["signint"]["usernamesignup"].value;
var y=document.forms["signint"]["passwordsignup"].value;
var z=document.forms["signint"]["emailsignup"].value;
var a=document.forms["signint"]["passwordsignup_confirm"].value;
if (x=== null || x==="")
  {
  alert("Username must be filled out");
  return false;
  }
else if (z=== null || z==="")
  {
  alert("Email must be filled out");
  return false;
  }
else if (y=== null || y==="")
  {
  alert("Password must be filled out");
  return false;
  }  
else if (a=== null || a==="")
  {
  alert("Retype Password");
  return false;
  }
 
var atpos=z.indexOf("@");
var dotpos=z.lastIndexOf(".");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=z.length)
  {
  alert("Not a valid e-mail address");
  return false;
  }
 else return true; 
}
