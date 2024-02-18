<?php
if (!empty($_FILES["files"]["name"][0])) {
    $files = $_FILES["files"];
    $uploadDir = $_POST["upload_dir"];
    $uploaded = [];
    $failed = [];

    foreach ($files["name"] as $index => $fileName) {
        $fileTmpName = $files["tmp_name"][$index];
        $fileSize = $files["size"][$index];
        $fileError = $files["error"][$index];

        if ($fileError == 0) {
            $fileDestination = $uploadDir . basename($fileName);
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $uploaded[] = $fileName;
            } else {
                $failed[] = [
                    "index" => $index,
                    "file_name" => $fileName,
                    "error_code" => $fileError,
                    "error_message" => "アップロードに失敗しました。"
                ];
                $failed[] = $fileName;
            }
        } else {
            switch ($fileError) {
                case UPLOAD_ERR_INI_SIZE:
                    $errorMessage = "アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $errorMessage = "アップロードされたファイルは、HTML フォームで指定された MAX_FILE_SIZE を超えています。";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errorMessage = "アップロードされたファイルは一部のみしかアップロードされていません。";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errorMessage = "ファイルはアップロードされませんでした。";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $errorMessage = "テンポラリフォルダがありません。";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $errorMessage = "ディスクへの書き込みに失敗しました。";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $errorMessage = "PHP の拡張モジュールがファイルのアップロードを中止しました。";
                    break;
                default:
                    $errorMessage = "Unknown upload error";
                    break;
            }
            $failed[] = [
                "index" => $index,
                "file_name" => $fileName,
                "error_code" => $fileError,
                "error_message" => $errorMessage
            ];
        }
    }

    $response = [
        "uploaded" => $uploaded,
        "failed" => $failed,
        "upload_dir" => $uploadDir
    ];
    echo json_encode($response);

} else {
    // upload_max_filesize, post_max_size を超えた場合もこの状態になる 
    echo json_encode(["error" => "アップロードに失敗しました。"]);
}
?>