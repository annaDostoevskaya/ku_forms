#!/usr/bin/env php
<?php

// function console_log($message) 
// {
//     $STDERR = fopen("php://stderr", "w");
//     fwrite($STDERR, $message."\n");
//     fclose($STDERR);
// }


// Script for initialize database with defaults variable and tables.

if(!is_dir('protected'))
{
    mkdir('protected');
}

if(!is_dir('protected/data'))
{
    mkdir('protected/data');
}

if(is_file('protected/data/kuforms.db'))
{
    // console_log('[dbinit.php] Database already exists!');
    exit(-1);
}

$db = new PDO('sqlite:' . __DIR__ . '/protected/data/kuforms.db');
$db->exec('PRAGMA foreign_keys = ON;');


$db->exec('
    CREATE TABLE IF NOT EXISTS forms (
        id                  INTEGER     PRIMARY KEY                     AUTOINCREMENT     NOT NULL,
        date                CHAR(128)   DEFAULT CURRENT_TIMESTAMP,
        author_name         CHAR(128)   DEFAULT "<No name>",
        author_email        CHAR(256)   DEFAULT "<No email>",
        subject             CHAR(256)   DEFAULT "<No subject>",
        description         CHAR(1024)  DEFAULT "<No description>",
        questions_number    INTEGER     NOT NULL,
        questions           TEXT        NOT NULL
    );
');

$db->exec('
    CREATE TABLE IF NOT EXISTS answers (
        id                      INTEGER     PRIMARY KEY                 AUTOINCREMENT   NOT NULL,
        answerer_name           CHAR(128)   DEFAULT "<No name>",
        answerer_email          CHAR(256)   DEFAULT "<No email>",
        date                    CHAR(128)   DEFAULT CURRENT_TIMESTAMP,
        answers                 TEXT        NOT NULL,
        form_id                 INTEGER     NOT NULL,
        FOREIGN KEY(form_id)   REFERENCES  forms(id)
    );
');

$first_form = '[
    {
        "tag":"input",
        "type":"text",
        "options":{
            "required":"true"
        },
        "content":"What is your name?"
    },
    {
        "tag":"input",
        "type":"date",
        "content":"When were you born?"
    },
    {
        "tag":"input",
        "type":"email",
        "content":"Please, give me your e-mail address."
    }
]';

$form_in_json = json_encode(
    json_decode(
        $first_form,
        true
    ), 
    true
);

$db->exec("
    INSERT INTO forms(
        id, 
        author_name, 
        author_email, 
        subject, 
        description, 
        questions_number,
        questions
    ) VALUES (
        0,
        'Anna Dostoevskaya',
        'iwantknow.aboutjt68h43@gmail.com',
        'My first form.',
        'This is my first form to test the kuforms app, hopefully it will stay here for as long as possible.',
        3,
        '{$form_in_json}'
    );
");

$db->exec("
    INSERT INTO answers(
        id,
        answerer_name,
        answerer_email,
        answers,
        form_id
    ) VALUES (
        0,
        'Anna Dostoevskaya',
        'iwantknow.aboutjt68h43@gmail.com',
        '[\"Anna Dostoevskaya\",\"1992-07-25\",\"iwantknow.aboutjt68h43@gmail.com\"]',
        0
    );
");

// console_log('[dbinit.php] Database initialized!');
?>