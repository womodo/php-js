<?php
// クラス定義ファイルのインクルード
require_once('./Person.php');
// クラスのインスタンス化
$person = new Person("John", 30);
// プロパティの取得
echo $person->getName() . "<br>";
echo $person->getAge() . "<br>";
// メソッドの実行
$person->introduce();
?>
