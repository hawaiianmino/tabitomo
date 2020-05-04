<?php
//共通変数・関数ファイル読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　チャットページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// 画面表示用データ取得
//================================
//GETパラメータの情報を格納
$u_id = (!empty($_GET['u_id'])) ? $_GET['u_id'] : '';
//DBから相手のユーザー情報取得
$yourInfo = getUser($u_id);
//自分のidを取得
$m_id = $_SESSION['user_id'];
//DBから自分の情報を取得
$myInfo = getUser($m_id);

// $p_info = $yourInfo['purpose'];
// $c_info = $yourInfo['country'];

//メッセージデータを取得
$viewData = getMsg($m_id,$u_id);

//パラメータに不正な値が入っていないかチェック
if(empty($u_id)){
    error_log('エラー発生：指定ページに不正な値が入りました');
    header('Location:index.php');
}

//GETパラメータに不正な値が入っていないかチェック
if(empty($yourInfo)){
    error_log('エラー発生：不正な値が入りました');
    header('Location:index.php');
}
debug('取得したDBデータ：'.print_r($yourInfo,true));

//post送信されていた場合
if(!empty($_POST) && isset($_REQUEST['send'])){
    debug('POST送信があります。');

    //ログイン認証
    require('auth.php');

    //例外処理
    try {
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'INSERT INTO bord (bord_id,send_date,to_user,from_user,msg,create_date) VALUES (:b_id,:send_date,:to_user,:from_user,:msg,:date )';
        $data = array(':b_id' => $u_id,':send_date' => date('Y-m-d H:i:s'),'to_user' => $yourInfo['id'],'from_user' => $_SESSION['user_id'],':msg' => $_POST['message'],':date' => date('Y-m-d H:i:s'));
        //クエリ実行
        $stmt = queryPost($dbh,$sql,$data);

        //クエリ成功の場合
        if($stmt){
            $_POST = array();//postをクリア
            debug('メッセージの投稿に成功しました。');
            header("Location:".$_SERVER['PHP_SELF']."?u_id=".$u_id);
        }
    }  catch (Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
    }

}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>
<body>
    <section id="chat" class="chat">
        <h2 class="txt-28 txt-center">メッセージ</h2>
        <div class="inner mt-20">
            <div class="chat__scroll">
                <?php foreach($viewData as $key => $val): ?>
                    <?php if($val['from_user'] == $m_id): ?>
                        <div class="chat__member1 mt-20">
                            <div class="chat__img1"><img src="<?= $myInfo['pic']; ?>" alt=""></div>
                            <p class="chat__txt1"><?= sanitize($val['msg']); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="chat__member2 mt-20">
                            <div class="chat__img2"><img src="<?= $yourInfo['pic']; ?>" alt=""></div>
                            <p class="chat__txt2"><?= sanitize($val['msg']); ?></p>
                        </div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
            <form action="" method="post" class="chat__msg mt-20">
                <div class="chat__input">
                    <textarea name="message" id="message" cols="30" rows="5"></textarea>
                    <div class="txt-right mt-10 mb-20">
                        <input type="submit" value="送信する" class="chat__btn" name="send">
                    </div>
                    <div class="return-btn txt-right">
                        <a href="index.php" class="txt-14">トップページへ戻る</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>
<?php require('footer.php'); ?>