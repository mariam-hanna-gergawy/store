<?php

require("libraries/DatabaseConnection.php");
require("libraries/Database.php");
require("core/Model.php");
require("core/Controller.php");
require("libraries/Validator.php");
require("helpers/validator.php");
require("libraries/Response.php");

require('Router.php');
require('Request.php');
require('Dispatcher.php');

$dispatch = new Dispatcher();
$dispatch->dispatch();
