@echo off

REM NOTE(annad): To pass all command line arguments %*


REM if not defined VARIABLE_SETUPED (
REM 	call setup_var
REM )
REM 

call setup_env
yii serve %*

REM TODO(annad): We must write script for initialize database. (! DB !)
REM TODO(annad): Find method set php.ini config for yii2, if PDO Driver SQLITE not start.

