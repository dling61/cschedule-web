<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

define('VERSION','1.2.0');
define('DEVICE','WEB');
define('SCODE','28e336ac6c9423d946ba02d19c6a2634');

define('CACHETIME','300');

set_time_limit(0);

require_once($yii);
require_once(dirname(__FILE__).'/protected/Common/function.php');
Yii::createWebApplication($config)->run();
