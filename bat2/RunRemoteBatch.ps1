# 信頼済みホストとして設定
#winrm set winrm/config/client '@{TrustedHosts="192.168.1.10"}'

# リモートPC情報
$RemoteComputer = "192.168.1.10"   # リモートPCのホスト名またはIPアドレス
$Username = "vboxuser"       # リモートPCのユーザー名
$Password = ConvertTo-SecureString "changeme" -AsPlainText -Force
$Credential = New-Object System.Management.Automation.PSCredential ($Username, $Password)

# 実行フォルダとバッチファイルのパス
$WorkingDirectory = "C:\Users\vboxuser\Desktop"  # バッチファイルが存在するディレクトリ
$BatFileName = "YourScript.bat"                   # 実行するバッチファイル名

# リモートでフォルダに移動してバッチファイルを実行
Invoke-Command -ComputerName $RemoteComputer -Credential $Credential -ScriptBlock {
    param($Path, $FileName)
    Set-Location -Path $Path         # 指定されたフォルダに移動
    & cmd.exe /c $FileName           # バッチファイルを実行
} -ArgumentList $WorkingDirectory, $BatFileName






# リモートでバッチファイルを実行し出力を取得
$output = Invoke-Command -ComputerName $RemoteComputer -Credential $Credential -ScriptBlock {
    param($Path, $FileName)
    Set-Location -Path $Path
    # バッチファイルを実行して出力を取得
    & cmd.exe /c $FileName 2>&1
} -ArgumentList $WorkingDirectory, $BatFileName

# 呼び出し元のコンソールに出力
Write-Output $output

Pause