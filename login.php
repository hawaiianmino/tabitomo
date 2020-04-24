<?php

//共通変数・関数ファイル読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// ログイン画面処理
//================================
//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');

    //変数にユーザー情報を格納
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    //email形式チェック
    validEmail($email,'email');
    //emailの最大文字数チェック
    validMaxLen($email,'email');

    //パスワード半角英数チェック
    validHalf($pass,'pass');
    //パスワード最大文字数チェック
    validMaxLen($pass,'pass');
    //パスワード最小文字数チェック
    validMinLen($pass,'pass');

    //未入力チェック
    validRequired($email,'email');
    validRequired($pass,'pass');

    // print_r($err_msg);

    if(empty($err_msg)){
        debug('バリデーションOKです。');

        //例外処理
        try {
            //DBへの接続
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'SELECT pass,id FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $email);
            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);
            //クエリ結果の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            debug('クエリ結果の中身：'.print_r($result,true));

            //パスワード格納
            $hash = array_shift($result);
            //パスワード照合
            if(!empty($result) && password_verify($pass, $hash)){
                debug('パスワードがマッチしました。');

                //ログイン有効期限(デフォルトの1時間)
                $sesLimit = 60*60;
                //最終ログイン日時を現在日時に
                $_SESSION['login_date'] = time();
                //ログイン有効期限を1時間にセット
                $_SESSION['login_limit'] = $sesLimit;

                //ユーザーIDを格納
                $_SESSION['user_id'] = $result['id'];

                debug('セッション変数の中身：'.print_r($_SESSION,true));
                debug('マイページへ遷移します。');
                header("Location:mypage.php");//マイページへ
            }else{
                debug('パスワードがアンマッチです。');
                $err_msg['common'] = MSG09;
            }
        } catch(Exception $e){
            error_log('エラー発生'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示終了　<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php require('head.php'); ?>
<!-- 1. header -->
<?php require('header.php'); ?>
<!-- 2. login -->
<section class="login">
    <div class="login__box">
        <p class="login__ttl"><?= (basename(__FILE__) == 'login.php') ? 'ログイン' : '新規登録'; ?></p>
        <form class="login__form" action="" method="post">
            <div class="mt-30">
                <label for="email" class="txt-18">Eメール</label>
                <input class="login__input" type="text" id="email" name="email" value="<?= (!empty($_POST['email']) ? $_POST['email'] : ''); ?>">
            </div>
            <div class="mt-30">
                <label for="pass" class="txt-18">パスワード</label>
                <input class="login__input" type="password" id="pass" name="pass" value="<?= (!empty($_POST['pass']) ? $_POST['pass'] : ''); ?>">
            </div>
            <div class="mt-30 txt-center">
                <input class="login__btn" type="submit" value="<?= (basename(__FILE__) == "login.php") ? 'ログイン' : '新規登録'; ?>">
            </div>
        </form>
    </div>
</section>
<body>
    
</body>
</html>