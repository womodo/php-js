@echo off
setlocal enabledelayedexpansion

:: �J�n���Ԃ̎擾
for /f "tokens=1-5 delims=.:/ " %%d in ("%date% %time%") do (
    set start_date=%%d-%%e-%%f
    set start_time=%%g:%%h:%%i
)
echo Batch Script Start: %start_date% %start_time% >> classuse.log

:: PHP�X�N���v�g�̎��s
php -f classuse.php >> classuse.log 2>&1
if %ERRORLEVEL% neq 0 (
    echo �G���[���������܂��� >> classuse.log
    exit /b 1
) else (
    echo ����ɏI�����܂��� >> classuse.log
)

:: �I�����Ԃ̎擾
for /f "tokens=1-5 delims=.:/ " %%d in ("%date% %time%") do (
    set end_date=%%d-%%e-%%f
    set end_time=%%g:%%h:%%i
)
echo Batch Script End: %end_date% %end_time% >> classuse.log

endlocal
