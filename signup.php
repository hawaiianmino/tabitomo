<?php

//共通変数・関数ファイル読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　新規登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 新規登録画面処理
//================================
//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');

    //変数にユーザー情報を格納
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    //未入力チェック
    validRequired($email,'email');
    validRequired($pass,'pass');
    validRequired($pass_re,'pass_re');

    if(empty($err_msg)){

        //email形式チェック
        validEmail($email,'email');
        //email最大文字数チェック
        validMaxLen($email,'email');

        //パスワード半角英数字チェック
        validHalf($pass,'pass');
        //パスワード最大文字数チェック
        validMaxLen($pass,'pass');
        //パスワード最小文字数チェック
        validMinLen($pass,'pass');

        //パスワード(再入力)最大文字数チェック
        validMaxLen($pass_re,'pass_re');
        //パスワード(再入力)最小文字数チェック
        validMinLen($pass_re,"pass_re");

        if(empty($err_msg)){

            //パスワードとパスワード(再入力)の値が一致しているかチェック
            validMatch($pass,$pass_re,'pass_re');

            if(empty($err_msg)){

                //例外処理
                try {
                    //DBヘ接続
                    $dbh = dbConnect();
                    //SQL文作成
                    $sql = 'INSERT INTO users (email,pass,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
                    $data = array(':email' => $email, ':pass' => password_hash($pass,PASSWORD_DEFAULT),':login_time' => date('Y-m-d H:i:s'),':create_date' => date('Y-m-d H:i:s'));

                //クエリ実行
                $stmt = queryPost($dbh,$sql,$data);

                //クエリ成功の場合
                if($stmt){
                    //ログイン有効期限を1時間にセット
                    $sesLimit = 60*60;
                    //最終ログイン日時を現在日時に
                    $_SESSION['login_date'] = time();
                    $_SESSION['login_limit'] = $sesLimit;
                    //ユーザーIDを格納
                    $_SESSION['user_id'] = $dbh->lastInsertId();

                    debug('セッションの中身：'.print_r($_SESSION,true));
                    header("Location:profEdit.php");//マイページへ
                }
                
                } catch(Exception $e){
                    error_log('エラー発生：'.$e->getMessage());
                    $err_msg['common'] = MSG07;
                }
            }
        }
    }
}
debug('画面表示終了　<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php require('head.php'); ?>
<!-- 1. header -->
<?php require('header.php'); ?>
<!-- 2. signup -->
<section class="signup">
    <div class="signup__box">
        <p class="signup__ttl">新規登録</p>
        <form class="signup__form" action="" method="post">
            <div class="mt-30">
                <label for="email" class="txt-18">Eメール</label>
                <input class="signup__input" type="text" id="email" name="email" value="<?= (!empty($_POST['email']) ? $_POST['email'] : ''); ?>">
            </div>
            <div class="mt-30">
                <label for="pass" class="txt-18">パスワード</label>
                <input class="signup__input" type="password" id="pass" name="pass" value="<?= (!empty($_POST['pass']) ? $_POST['pass'] : ''); ?>">
            </div>
            <div class="mt-30">
                <label for="pass_re" class="txt-18">パスワード(再入力)</label>
                <input class="signup__input" type="password" id="pass_re" name="pass_re" value="<?= (!empty($_POST['pass_re']) ? $_POST['pass_re'] : ''); ?>">
            </div>
            <div class="mt-30 txt-center">
                <input class="signup__btn" type="submit" value="登録する">
            </div>
        </form>
    </div>
</section>
<body>
    
</body>
</html>