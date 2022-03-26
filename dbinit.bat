@echo off

REM Bat Wrapper Script for initialize database with defaults variable and tables.

@setlocal

set PATH_TO_DB_INIT=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%PATH_TO_DB_INIT%dbinit.php" %*

if %errorlevel% == -1 (
	echo [dbinit.php] Database [32malready exists[0m!
) else (
	if %errorlevel% == 0 (
		echo [dbinit.php] Database [32minitialized[0m!
	) else (
		echo [dbinit.php] Unknow [91merror[0m!
		exit /b 1
	)
)

@endlocal