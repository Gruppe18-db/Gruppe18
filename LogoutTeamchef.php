// Autor: Dilara Öztürk
<?php
session_start();
session_destroy();
header("Location: LoginTeamchef.php");
exit;
?>