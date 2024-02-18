<?php
$dbname = "phpjs";
$servername = "localhost";
$username = "root";
$password = "zaq12wsx";
$dsn = "mysql:dbname=".$dbname.";host=".$servername;
$dbh = new PDO($dsn, $username, $password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event = htmlspecialchars($_POST["event"]);

    if ($event == "save") {
        $fileSelect = $_POST["fileSelect"];
        foreach ($fileSelect as $index => $value) {
            // echo $index . ' ' . $value . '<br>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ファイルアップロード</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <form method="post" name="frm1">
        <div class="container-fluid mt-3 mb-3">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            画像リスト
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <input type="file" id="fileInput1" accept="image/*" style="display:none;" multiple>
                                    <input type="button" id="fileSelectButton1" value="画像1を選択">    
                                </div>
                                <div class="col-6">
                                    <ul id="fileList" style="margin-bottom:0;"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            画像セレクト
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <input type="file" id="fileInput2" accept="image/*" style="display:none;" multiple>
                                    <input type="button" id="fileSelectButton2" value="画像2を選択">
                                </div>
                                <div class="col-8">
                                    <table>
                                        <tbody id="tbody">
                                            <?php
                                            $data = "";
                                            $sql = "SELECT * FROM t_select ";
                                            $stmt = $dbh->query($sql);
                                            while($value = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <select name="fileSelect[<?=$value["ID"]?>]" id="fileSelect[<?=$value["ID"]?>]" style="width:100px;">
                                                            <option value="<?=$value["FILE_PATH"]?>" selected></option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="diagram" id="selectFileName[<?=$value["ID"]?>]"></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3 mb-3">
            <input type="button" id="save" value="保存">
        </div>

        <input type="hidden" name="event" value="<?=$event?>">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.min.js"></script>
    <script>
        const MOUSE_OVER_PREVIEW = 'mouseOverPreview';
        const UPLOAD_DIR = 'images/upload/';

        // ポップアップ画面用
        var popupWindowList = {};

        $(document).ready(function() {
            // 一覧を取得
            getImagesTypeL();
            // セレクトを取得
            getImagesTypeS();

            // 保存ボタンを押した時
            $('#save').click(function() {
                document.frm1.event.value = "save";
                document.frm1.submit();
            });

            // 画像1を選択ボタンを押した時
            $('#fileSelectButton1').click(function() {
                $('#fileInput1').click();
            });
            // ファイル選択画面1が表示・変更された後
            $('#fileInput1').change(function() {
                handleImageUpload(event, 'L');
            });

            // 画像2を選択ボタンを押した時
            $('#fileSelectButton2').click(function() {
                $('#fileInput2').click();
            });
            // ファイル選択画面2が表示・変更された後
            $('#fileInput2').change(function() {
                handleImageUpload(event, 'S');
            });

            // リストの画像のリンクにマウスカーソルをのせた時
            $('#fileList').on('mouseenter', '.diagram', function(event) {
                var id = this.id.replace('[', '\\[').replace(']', '\\]');
                var name = $(this).text();
                openPreviewWindow(event, id, name, MOUSE_OVER_PREVIEW);
            });
            // リストの画像のリンクからマウスカーソルが外れた時
            $('#fileList').on('mouseleave', '.diagram', function(event) {
                closePreviewWindow(MOUSE_OVER_PREVIEW);
            });
            // リストの画像のリンクをクリックした時
            $('#fileList').on('click', '.diagram', function(event) {
                event.preventDefault();
                var id = this.id.replace('[', '\\[').replace(']', '\\]');
                var name = $(this).text();
                openPreviewWindow(event, id, name);
            });

            // ×ボタンがクリックされた時
            $('#fileList').on('click', '.deleteItem', function(event) {
                event.preventDefault();

                if(confirm('削除してよろしいですか？')) {
                    // $(this).parent().remove();
                    var filePath = $(this).data('filename');
                    $.ajax({
                        url: 'ajaxImage.php',
                        type: 'POST',
                        data: {
                            target: 'DELETE',
                            IMG_TYPE: 'L',
                            FILE_PATH: filePath,
                        },
                        dataType: 'json',
                        success: function(data) {
                            // 一覧を更新
                            getImagesTypeL();
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                }
            });

            // ドロップダウンから画像を選択した時
            $('select[id^=fileSelect]').on('change', function() {
                var id = this.id.replace('fileSelect', '').replace('[', '\\[').replace(']', '\\]');
                var path = $(this).val();
                var text = $(this).find('option:selected').text();
                $('#selectFileName'+id).text(text);
                $('#selectFileName'+id).data('path', path);
            });

            // 画像のリンクにマウスカーソルをのせた時
            $('a[id^=selectFileName]').on('mouseenter', function(event) {
                var id = this.id.replace('[', '\\[').replace(']', '\\]');
                var name = $(this).text();
                openPreviewWindow(event, id, name, MOUSE_OVER_PREVIEW);
            });
            // 画像のリンクからマウスカーソルが外れた時
            $('a[id^=selectFileName]').on('mouseleave', function(event) {
                closePreviewWindow(MOUSE_OVER_PREVIEW);
            });
            // 画像のリンクをクリックした時
            $('a[id^=selectFileName]').on('click', function(event) {
                event.preventDefault();
                var id = this.id.replace('[', '\\[').replace(']', '\\]');
                var name = $(this).text();
                openPreviewWindow(event, id, name);
            });
        });

        // ファイル(L)を取得
        function getImagesTypeL() {
            $.ajax({
                url: 'ajaxImage.php',
                type: 'POST',
                data: {
                    target: 'GET',
                    IMG_TYPE: 'L',
                },
                dataType: 'json',
                success: function(data) {
                    $('#fileList').empty();
                    $.each(data, function(index, value) {
                        var no = $('#fileList li').length;
                        var path = value.FILE_PATH;
                        var item = $(`<li>
                                        <a href="#" class="diagram" id="diagram[${no}]" data-path="${path}">画像L[${no+1}]</a>
                                        <a href="#" class="deleteItem" data-filename="${path}" style="text-decoration:none; color:black;">✖</a>
                                    </li>`);
                        $('#fileList').append(item);
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        // ファイル(S)を取得
        function getImagesTypeS() {
            $.ajax({
                url: 'ajaxImage.php',
                type: 'POST',
                data: {
                    target: 'GET',
                    IMG_TYPE: 'S',
                },
                dataType: 'json',
                success: function(data) {
                    var fileSelectList = $.find('select[id^="fileSelect"]');
                    $.each(fileSelectList, function(index, fileSelect) {
                        var id = this.id.replace('fileSelect', '').replace('[', '\\[').replace(']', '\\]');
                        var selectVal = '';
                        $.each(data, function(idx, value) {
                            if (idx == 0) {
                                selectVal = $(fileSelect).val();
                                $(fileSelect).empty();
                                $(fileSelect).append('<option value=""></option>');
                            }
                            var path = value.FILE_PATH;
                            var item = $(`<option value="${path}" ${path == selectVal ? "selected" : ""}>画像S[${idx+1}]</option>`);
                            $(fileSelect).append(item);
                            
                            if (path == selectVal) {
                                $(`#selectFileName${id}`).text(`画像S[${idx+1}]`);
                                $(`#selectFileName${id}`).data('path', path);
                            }
                        });
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        // ファイルを圧縮してアップロード（複数ファイル可能）
        function handleImageUpload(event, inputType) {
            var files = event.target.files;
            var now = Date.now().toString();

            if (files.length > 0) {
                // 圧縮設定
                var options = {
                    maxSizeMB: 1,
                    maxWidthOrHeight: 1600,
                    useWebWorker: false
                };
                // 圧縮処理
                var compressedFiles = [];
                var fileNames = [];
                var uploadFileNames = [];
                $.each(files, function(index, file) {
                    var fileType = file.name.split('.').pop();
                    var uploadfileName = 'IMG_' + now + index + '.' + fileType;
                    fileNames.push(file.name);
                    uploadFileNames.push(uploadfileName);
                    compressedFiles.push(imageCompression(file, options));
                });

                //圧縮されたファイルをアップロードする
                Promise.all(compressedFiles).then(function(compressedFilesArray) {
                    var formData = new FormData();
                    formData.append('upload_dir', UPLOAD_DIR);
                    $.each(compressedFilesArray, function(index, compressedFile) {
                        formData.append('files[]', compressedFile, uploadFileNames[index]);
                    });

                    $.ajax({
                        url: 'upload.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.error) {
                                // アップロードに失敗した場合
                                alert(response.error);

                            } else {
                                // アップロードに失敗したファイル
                                if (response.failed.length > 0) {
                                    var errmsg = '以下のファイルがアップロードできませんでした。\n';
                                    $.each(response.failed, function(index, value) {
                                        errmsg += '\n' + fileNames[value.index] + '\n［Error: ' + value.error_message + '］';
                                    });
                                    alert(errmsg);
                                }
                                // アップロードに成功したファイル
                                var filePathList = [];
                                $.each(response.uploaded, function(index, value) {
                                    filePathList.push(UPLOAD_DIR + value);
                                });

                                // テーブルへ登録
                                if (filePathList.length > 0) {
                                    $.ajax({
                                        url: 'ajaxImage.php',
                                        type: 'POST',
                                        data: {
                                            target: 'INSERT',
                                            IMG_TYPE: inputType,
                                            FILE_PATH_LIST: filePathList.join(','),
                                        },
                                        dataType: 'json',
                                        success: function(data) {
                                            // 一覧を更新
                                            if (inputType == 'L') {
                                                getImagesTypeL();
                                            }
                                            // セレクトを更新
                                            if (inputType == 'S') {
                                                getImagesTypeS();
                                            }
                                        },
                                        error: function(xhr, textStatus, errorThrown) {
                                            alert(errorThrown);
                                        }
                                    });
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, error) {
                            console.log(error);
                            alert('アップロード中にエラーが発生しました。');
                        }
                    });

                }).catch(function(error) {
                    console.log(error);
                    alert('ファイルの圧縮中にエラーが発生しました。');
                });
            }
        }

        // 画像ファイルのプレビュー画面を表示
        function openPreviewWindow(event, id, title, target) {
            // ポップアップで画像のプレビューを表示
            var imagePath = $('#'+id).data('path');
            var posX = event.screenX + 20;
            var posY = event.screenY;
            if (!target) {
                target = title;
            }

            $('<img/>').attr('src', imagePath).on('load', function() {
                var widthOrHeight = 600;
                var popupWidth, popupHeight;

                if (this.width > this.height) {
                    popupWidth = widthOrHeight;
                    popupHeight = Math.round(this.height * widthOrHeight / this.width);
                } else {
                    popupWidth = Math.round(this.width * widthOrHeight / this.height);
                    popupHeight = widthOrHeight;
                }
                
                var popupWindow = window.open('popup.php', target, `width=${popupWidth},height=${popupHeight},left=${posX},top=${posY}`);
                var form = $('<form/>', { action: 'popup.php', method: 'post', target: target })
                    .append( $('<input/>', { type: 'hidden', name: 'img_src', value: imagePath } ) )
                    .append( $('<input/>', { type: 'hidden', name: 'window_title', value: title } ) )
                    .appendTo( document.body );
                form.submit();
                form.remove();

                popupWindowList[target] = popupWindow;
            });
        }

        // プレビュー画面を閉じる
        function closePreviewWindow(target) {
            $.each(popupWindowList, function(key, popupWindow) {
                if (popupWindow && !popupWindow.closed && event.target !== popupWindow) {
                    if (target) {
                        if (key == target) {
                            popupWindow.close();
                            delete popupWindowList[key];
                        }
                    } else {
                        popupWindow.close();
                        delete popupWindowList[key];
                    }
                }
            });
        }

        // 画面を閉じる時にプレビュー画面を閉じる
        $(window).on('beforeunload', function() {
            closePreviewWindow();
        });
    </script>
</body>
</html>