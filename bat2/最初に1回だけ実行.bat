@echo off
:: �Ǘ��Ҍ����Ŏ��s���邽�߂̏��i�m�F
:: �Ǘ��҂łȂ��ꍇ�A�Ǘ��Ҍ����ōĎ��s
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo �Ǘ��Ҍ����ōĎ��s���܂�...
    powershell -Command "Start-Process cmd -ArgumentList '/c %~f0' -Verb runAs"
    exit /b
)

:: �Ǘ��Ҍ����� WinRM �ݒ�����s
echo WinRM �N���C�A���g�ݒ��ύX��...

:: ���s���ʂ�\��
winrm set winrm/config/client @{TrustedHosts="192.168.1.10"} 2>&1 | more

:: �I����ҋ@
echo.
echo �R�}���h�̎��s���������܂����BEnter�L�[�������ďI�����Ă��������B
pause >nul
