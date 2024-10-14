<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <!-- Bootstrap 5のCSSを読み込む -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">   -->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDGothic&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
</head>
    <style>
        html, body {
            /* font-family: 'Noto Sans JP', sans-serif; */
            /* font-family: 'BIZ UD Gothic', sans-serif; */
            font-family: "Zen Kaku Gothic New", sans-serif;
            font-weight: 500 !important;
        }

        input, textarea {
            font-weight: 500 !important;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        @media (max-width: 576px) {
            .container {
                /* align-items: flex-start;
                padding-top: 20px;
                height: auto; */
                height: 100dvh;
            }
        }
    </style>
</head>

<body class="bg-light">

    <section>
        <div class="container">
            <div class="card border-light-subtle shadow" style="max-width: 640px;">
                <div class="row g-0">
                    <div class="col-12 col-md-7">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h3 class="text-center">Log in</h3>
                                    </div>
                                    
                                    <div class="m-4">
                                        <form method="post">
                                            <div class="row gy-3 gy-md-3">
                                                <div class="col-12">
                                                    <label for="userid" class="form-label">ユーザーID</label>
                                                    <input type="text" class="form-control" name="userid" id="userid" autocomplete="username">
                                                </div>
                                                <div class="col-12">
                                                    <label for="password" class="form-label">パスワード</label>
                                                    <input type="password" class="form-control" name="password" id="password" autocomplete="current-password">
                                                </div>
                                                <div class="col-12 pt-4">
                                                    <div class="d-grid pb-3">
                                                        <button class="btn btn-primary" type="submit">ログイン</button>
                                                    </div>
                                                    <div class="d-grid">
                                                        <button class="btn btn-secondary" type="button">表示</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 text-bg-primary pb-3 pb-md-0">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="col-10 col-xl-8 text-center">
                                <h2 class="h4 mt-4">スマホアプリ</h2>
                                <hr class="border-primary-subtle mb-4">
                                <div class="d-flex justify-content-center mb-4">
                                    <a href="https://www.google.co.jp"><div id="qrcode" style="width:150px; height:150px;"></div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5のJSを読み込む -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script type="text/javascript">
        // QRコードを生成
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "https://www.google.co.jp",
            width: 150,
            height: 150,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
</body>

</html>