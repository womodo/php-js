<?php

// 指定した日付
$specified_date = '2024-09-01';

// 指定した日付から2か月前の日付を計算
$target_date = strtotime('-2 months', strtotime($specified_date));

// 対象ディレクトリ
$directory = "./zips/";

// 対象となるZIPファイルを保持する配列
$zip_files = glob($directory . '*.zip');

// 対象となるファイルを保持する配列
$target_files = [];

// ZIPファイルごとにループして更新日を確認
foreach ($zip_files as $file) {
    // ファイルの最終更新日時を取得
    $file_mod_time = filemtime($file);

    // 指定した日から2か月前より新しいファイルだけを対象とする
    if ($file_mod_time >= $target_date) {
        // 対象ファイルリストに追加
        $target_files[] = $file;
    }
}

sort($target_files);

foreach ($target_files as $zipFilePath) {
    // ZIPファイルを開く
    $zip = new ZipArchive;
    if ($zip->open($zipFilePath) === TRUE) {
        echo "ZIPファイルを処理中： " . basename($zipFilePath) . "<br>";

        // ZIPファイル内のファイル情報を格納する配列
        $files = [];

        // ZIPファイル内のファイル数を取得し、ファイル情報を配列に格納
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileInfo = $zip->statIndex($i);
            // CSVファイルのみを配列に追加
            if (pathinfo($fileInfo["name"], PATHINFO_EXTENSION) === "csv") {
                $files[] = $fileInfo;
            }
        }

        // ファイル名でソート
        usort($files, function($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });

        // ソートされた順番でCSVファイルを処理
        foreach ($files as $fileInfo) {
            echo "CSVファイルが見つかりました： " . $fileInfo["name"] . "<br>";

            // CSVファイルの内容を直接メモリ上に読み込む
            $fp = $zip->getStream($fileInfo["name"]);
            if ($fp) {
                // CSVの内容を行ごとに読み込む
                while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
                    // 読み込んだデータを処理
                    print_r($data);
                    echo "<br>";
                }
            } else {
                echo "CSVファイルが開けませんでした： " . $fileInfo["name"] . "<br>";
            }
        }

    } else {
        echo "ZIPファイルが開けませんでした： " . basename($zipFilePath) . "<br>";
    }
}
?>