@echo off
REM NOTE(annad): To pass all command line arguments %*

call .\protected\runtime\setup_env.bat
call .\dbinit.bat

yii serve %*

