@echo off
powershell.exe -executionpolicy RemoteSigned -File "%~dp0%~n0.ps1"


:: 管理者権限でPowerShellスクリプトを実行
:: set "psScriptPath=C:\Users\masayuki\Desktop\RunRemoteBatch.ps1"
:: powershell -Command "Start-Process powershell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -File %psScriptPath%' -Verb runAs"
