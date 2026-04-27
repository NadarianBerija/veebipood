<?php
session_start();

require '../inc/Database.php';

require 'modelAdmin/Login.php';

require 'controllerAdmin/controllerAdmin.php';
require 'routeAdmin/routingAdmin.php';

echo $response;
?>