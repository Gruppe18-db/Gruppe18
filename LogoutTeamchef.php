<?php
// Autor: Dilara Öztürk
session_start();
session_destroy();
header("Location: LoginTeamchef.php");
exit;
?>