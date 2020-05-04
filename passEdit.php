<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード変更ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================
// DBからユーザーデータを取得
$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($userData,true));

if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST,true));

    //変数にユーザー情報を格納
    $pass_old = $_POST['pass_old'];
    $pass_new = $_POST['pass_new'];
    $pass_new_re = $_POST['pass_new_re'];

    //未入力チェック
    validRequired($pass_old,'pass_old');
    validRequired($pass_new,'pass_new');
    validRequired($pass_new_re,'pass_new_re');

    if(empty($err_msg)){
        debug('未入力チェックOK');

        //古いパスワードのチェック
        validPass($pass_old,'pass_old');
        validPass($pass_new,'pass_new');
        
        //古いパスワードとDBのパスワードを比較
        if(!password_verify($pass_old,$userData['pass'])){
            $err_msg['pass_old'] = MSG12;
        }

        //古いパスワードと新しいパスワードを比較
        if($pass_old === $pass_new){
            $err_msg['pass_new'] = MSG13;
        }

        //新しいパスワードとパスワード再入力があっているかチェック
        validMatch($pass_new,$pass_new_re,'pass_new_re');

        if(empty($err_msg)){
            debug('バリデーションOK');

            //例外処理
            try {
                //DBへ接続
                $dbh = dbConnect();
                //SQL文を作成
                $sql = 'UPDATE users SET pass = :pass where id = :id';
                $data = array(':id' => $_SESSION['user_id'],':pass' => password_hash($pass_new,PASSWORD_DEFAULT));
                //クエリ実行
                $stmt = queryPost($dbh,$sql,$data);

                //クエリ成功の場合
                if($stmt){
                    $_SESSION['msg_success'] = SUC01;

                    //メールを送信
                    $username = ($userData['name']) ? $userData['name'] : '名前なし';
                    $from = '50storm@info';
                    $to = $userData['email'];
                    $subject = 'パスワード変更通知 | 旅トモ';
                    $comment = <<<EOT
{$username}さん
パスワードが変更されました。

////////////////////////////////////////
ウェブカツマーケットカスタマーセンター
URL  http://50storm.info/
E-mail info@50storm.com
////////////////////////////////////////
EOT;
                    sendMail($from,$to,$subject,$comment);

                    //マイページへ遷移
                    header('Location:index.php');






                }
            } catch (Exception $e){
                error_log('エラー発生：'.$e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}

?>


<?php require('head.php'); ?>
<!-- 1. header -->
<?php require('header.php'); ?>
<!-- 2. signup -->
<section class="signup">
    <div class="signup__box">
        <p class="signup__ttl">パスワード変更</p>
        <form class="signup__form" action="" method="post">
            <div class="mt-30">
                <label for="pass_old" class="txt-18">古いパスワード</label>
                <input class="signup__input" type="text" id="pass_old" name="pass_old" value="<?= (!empty($_POST['pass_old']) ? $_POST['pass_old'] : ''); ?>">
            </div>
            <div class="area-msg">
                <?= (!empty($err_msg['pass_old'])) ? $err_msg['pass_old'] : ''; ?>
            </div>
            <div class="mt-30">
                <label for="pass_new" class="txt-18">新しいパスワード</label>
                <input class="signup__input" type="password" id="pass_new" name="pass_new" value="<?= (!empty($_POST['pass_new']) ? $_POST['pass_new'] : ''); ?>">
            </div>
            <div class="area-msg">
                <?= (!empty($err_msg['pass_new'])) ? $err_msg['pass_new'] : ''; ?>
            </div>
            <div class="mt-30">
                <label for="pass_new_re" class="txt-18">新しいパスワード(再入力)</label>
                <input class="signup__input" type="password" id="pass_new_re" name="pass_new_re" value="<?= (!empty($_POST['pass_new_re']) ? $_POST['pass_new_re'] : ''); ?>">
            </div>
            <div class="area-msg">
                <?= (!empty($err_msg['pass_new_re'])) ? $err_msg['pass_new_re'] : ''; ?>
            </div>
            <div class="mt-30 txt-center">
                <input class="signup__btn" type="submit" value="変更する">
            </div>
        </form>
    </div>
</section>
<body>
    
</body>
</html>