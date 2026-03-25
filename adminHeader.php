<?php

require_once '../includes/session.php'; 
require_once '../includes/db_connection.php'; 

checkAccess(['Admin']); 

$adminName = $_SESSION['user_name'];
$adminId   = $_SESSION['user_id'];
