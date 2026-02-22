
<?php
session_start();

unset($_SESSION['user']);   

unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['role']);

/* Security */
session_regenerate_id(true);


header("Location: index.php");
exit();
?>

