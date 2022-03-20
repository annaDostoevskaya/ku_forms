<?php

$path_to_database = __DIR__ . '/../kuforms.db';

return [
    'class' => 'yii\db\Connection',

    // TODO(annad )We must write script for initialize database.
    'dsn' => 'sqlite:' . $path_to_database, 
    
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
