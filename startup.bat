@echo off
REM NOTE(annad): To pass all command line arguments %*

call .\protected\runtime\setup_env.bat
call .\dbinit.bat

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

yii serve %* -p=80 

