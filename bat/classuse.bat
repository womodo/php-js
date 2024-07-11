@echo off
setlocal enabledelayedexpansion

:: 開始時間の取得
for /f "tokens=1-5 delims=.:/ " %%d in ("%date% %time%") do (
    set start_date=%%d-%%e-%%f
    set start_time=%%g:%%h:%%i
)
echo Batch Script Start: %start_date% %start_time% >> classuse.log

:: PHPスクリプトの実行
php -f classuse.php >> classuse.log 2>&1
if %ERRORLEVEL% neq 0 (
    echo エラーが発生しました >> classuse.log
    exit /b 1
) else (
    echo 正常に終了しました >> classuse.log
)

:: 終了時間の取得
for /f "tokens=1-5 delims=.:/ " %%d in ("%date% %time%") do (
    set end_date=%%d-%%e-%%f
    set end_time=%%g:%%h:%%i
)
echo Batch Script End: %end_date% %end_time% >> classuse.log

endlocal
