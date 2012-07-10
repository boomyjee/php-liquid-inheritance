<?php
define('LIQUID_INCLUDE_SUFFIX', 'tpl');
define('LIQUID_INCLUDE_PREFIX', '');

require_once('./Liquid.class.php');
require_once('../LiquidTagBlock.php');
require_once('../LiquidTagExtends.php');

define('BASE_PATH', dirname(__FILE__));

$liquid = new LiquidTemplate(BASE_PATH . '/templates/');
$liquid->registerTag('title','LiquidTagBlock');
$liquid->parse(file_get_contents(BASE_PATH.'/templates/default.tpl'));

echo $liquid->render();

