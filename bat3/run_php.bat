@echo off
set LOGFILE=php_execution.log
echo [%DATE% %TIME%] PHP�X�N���v�g�����s�J�n >> %LOGFILE%

php script.php >> %LOGFILE% 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [%DATE% %TIME%] �G���[����: PHP�X�N���v�g���ُ�I�����܂����B >> %LOGFILE%
) else (
    echo [%DATE% %TIME%] PHP�X�N���v�g������I�����܂����B >> %LOGFILE%
)

echo [%DATE% %TIME%] PHP�X�N���v�g�����s�I�� >> %LOGFILE%
exit /b %ERRORLEVEL%