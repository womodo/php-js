<?php
echo "aaa";
// $pythonScriptPath = "C:\Apache24\htdocs\php-js\bootstrap\script.py\script.py";
// $output = shell_exec("C:\Users\masayuki\AppData\Local\Programs\Python\Python312\python.exe" . escapeshellarg($pythonScriptPath));
// echo $output;

// 実行したいPythonスクリプトのパス
$pythonScript = 'C:\Apache24\htdocs\php-js\bootstrap\script.py';

// 引数として渡したい名前
$name = 'PHP';

// Pythonスクリプトを実行
$command = escapeshellcmd("C:\Users\masayuki\AppData\Local\Programs\Python\Python312\python.exe $pythonScript $name");
// echo $command;
$output = shell_exec($command);


// Pythonスクリプトの出力を表示
echo "Python Script Output: " . $output;
?>