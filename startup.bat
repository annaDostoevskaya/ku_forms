@echo off
if not defined VARIABLE_SETUPED (
	call setup_var
)

yii serve
