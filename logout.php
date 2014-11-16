<?php require('includes/config.php');

$user->logout(); 

unset($_SESSION['userid']);
unset($_SESSION['id']);
unset($_SESSION['start']);
unset($_SESSION['idno']);
unset($_SESSION['sess']);
?>
<script>
    window.close();
    </script>
    <meta http-equiv="refresh" content="0,index.php">
