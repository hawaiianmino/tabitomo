<?php
//共通変数・関数ファイル読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール詳細ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

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

$p_info = $yourInfo['purpose'];
$c_info = $yourInfo['country'];

//メッセージデータを取得
$viewData = getMsg($m_id,$u_id);
$viewData2 = getMsg($u_id,$m_id);
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

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>
<body>
    <section id="profile" class="profile">
        <div class="inner">
            <div class="profile__top">
                <div class="profile__img">
                    <img src="<?= $yourInfo['pic']; ?>" alt="プロフィール画像">
                </div>
                <p class="txt-center mt-10 txt-28"><?= $yourInfo['name']; ?></p>
                <div class="txt-center mt-10">
                    <a href="<?= 'msg.php?u_id='.$u_id; ?>" class="chat__btn txt-18">会話したい</a>
                </div>
            </div>
            <hr>
            <div class="profile__wrap mt-20">
                <h2 class="txt-28 txt-center">PROFILE</h2>
                <div class="mt-20">
                    <p><?= $yourInfo['greeting']; ?></p>
                </div>
                <table class="profile__info mt-20">
                    <tr><td>名前(ニックネーム)</td><td><?= $yourInfo['name'] ?></td></tr>
                    <tr><td>性別</td><td><?= $yourInfo['sex']; ?></td></tr>
                    <tr><td>滞在地</td><td><?= getCountry($c_info); ?></td></tr>
                    <tr><td>目的</td><td><?= getPurpose($p_info); ?></td></tr>
                </table>
                <div class="txt-center mt-20 mb-20">
                    <a href="<?= 'msg.php?u_id='.$u_id; ?>" class="chat__btn txt-18">会話したい</a>
                </div>
                <div class="return-btn txt-center mb-20 txt-14">
                    <a href="index.php">前画面へ戻る</a>
                </div>
            </div>
        </div>
    </section>
</body>
<?php require('footer.php'); ?>