<?php

use Smarty\Smarty;

require_once 'vendor/autoload.php';

$smarty = new Smarty();

$smarty->setTemplateDir(__DIR__ . '/src/templates/');
$smarty->setCompileDir(__DIR__ . '/src/templates_c/');

$smarty->caching = false;
$smarty->compile_check = true;
?>