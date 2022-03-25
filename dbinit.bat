@echo off

REM Bat Wrapper Script for initialize database with defaults variable and tables.

@setlocal

set PATH_TO_DB_INIT=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%PATH_TO_DB_INIT%dbinit.php" %*

@endlocal