@echo off
:: 管理者権限で実行するための昇格確認
:: 管理者でない場合、管理者権限で再実行
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo 管理者権限で再実行します...
    powershell -Command "Start-Process cmd -ArgumentList '/c %~f0' -Verb runAs"
    exit /b
)

:: 管理者権限で WinRM 設定を実行
echo WinRM クライアント設定を変更中...

:: 実行結果を表示
winrm set winrm/config/client @{TrustedHosts="192.168.1.10"} 2>&1 | more

:: 終了を待機
echo.
echo コマンドの実行が完了しました。Enterキーを押して終了してください。
pause >nul
