<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　退会ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');
    //例外処理
    try {
        //DBへ接続
        $dbh = dbConnect();
        //SQL文を作成
        $sql = 'UPDATE users SET delete_flg = 1 WHERE id = :u_id';
        $data = array(':u_id' => $_SESSION['user_id']);
        $stmt = queryPost($dbh,$sql,$data);

        //クエリ成功の場合は以下の操作を実施
        if($stmt){
            //セッション削除
            session_destroy();
            debug('セッション変数の中身：'.print_r($_SESSION,true));
            debug('トップページへ遷移します');
            header("Location:index.php");
        }else{
            debug('クエリ失敗しました。');
            $err_msg['common'] = MSG07;
        }
    } catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php require('head.php'); ?>
<!-- 1. header -->
<?php require('header.php'); ?>
<!-- 2. login -->
<section class="login">
    <div class="login__box">
        <p class="login__ttl">本当に退会しますか？</p>
        <form class="login__form" action="" method="post">
            <div class="mt-30 txt-center">
                <input class="login__btn" type="submit" value="退会する" name="submit">
            </div>
            <div class="return-btn txt-center mt-20 txt-14">
                <a href="profEdit.php">プロフィール編集に戻る</a>
            </div>
        </form>
    </div>
</section>
<body>
    
</body>
</html>