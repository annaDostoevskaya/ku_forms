<?php

if(getenv('APPLICATION-ENV') == 'dev')
{
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_ENV') or define('YII_ENV', 'dev');
}

// TODO(annad): IT'S NOT NORMAL!! We must delete this. when find method startup script from heroku.
exec("php " . __DIR__ . '/../dbinit.php');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
