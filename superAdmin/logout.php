<?php
session_start();
session_destroy();
header("Location: login"); // Redirect to login page
exit();
?>
