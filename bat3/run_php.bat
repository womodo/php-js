@echo off
set LOGFILE=php_execution.log
echo [%DATE% %TIME%] PHPスクリプトを実行開始 >> %LOGFILE%

php script.php >> %LOGFILE% 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [%DATE% %TIME%] エラー発生: PHPスクリプトが異常終了しました。 >> %LOGFILE%
) else (
    echo [%DATE% %TIME%] PHPスクリプトが正常終了しました。 >> %LOGFILE%
)

echo [%DATE% %TIME%] PHPスクリプトを実行終了 >> %LOGFILE%
exit /b %ERRORLEVEL%