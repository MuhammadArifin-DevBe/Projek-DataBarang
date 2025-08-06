<?php
session_start();

require 'admin/function.php';

catat_log("Logout dari sistem");

$_SESSION = [];
session_unset();
session_destroy();

header("Location: login.php");
exit;
