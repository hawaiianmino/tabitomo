<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================
//ユーザーIDのGETパラメーターを取得
$userData = getUserList();
//国のリストを取得
$countryData = getCountryList();

//GET情報を取得
$country = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
$sex = (!empty($_GET['sex'])) ? $_GET['sex'] : '';

//サーチ結果を取得
$dbSearchData = getSearchList($country,$sex);

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php require('head.php');?>
<?php require('header.php'); ?>
<body>
    <p id="js-show-msg" class="msg-slide" style="display: none;">
        <?= getSessionFlash('msg_success'); ?>
    </p>
    <section class="main-view">
        <div class="main-view__txt">
            <h2 class="txt-36">1人もいいけど、一緒はもっと楽しい。</h2>
        </div>
    </section>
    <section class="member">
        <div class="inner">
            <div class="member__search">
                <form action="" method="get">
                    <p class="member__myMenu-ttl">検索</p>
                    <div class="member__search-item">
                        <label for="country">滞在先：</label>
                        <select name="c_id" id="country" class="mt-10">
                            <?php foreach($countryData as $key => $val): ?>
                            <option value="<?= $val['id'] ?>" <?= ($country == $val['id']) ? 'selected' : ''; ?>><?= $val['country_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="member__search-item mt-20">
                        <label for="sex">性別：</label>
                        <select name="sex" id="sex" class="mt-10">
                            <option value="男" <?= ($sex == '男') ? 'selected' : ''; ?>>男性</option>
                            <option value="女" <?= ($sex == '女') ? 'selected' : ''; ?>>女性</option>
                        </select>
                    </div>
                    <div class="mt-20">
                        <input type="submit" class="member__btn txt-14" value="検索する">
                    </div>
                    <hr class="mt-20 mb-20">
                    <div class="member__myMenu">
                        <p class="member__myMenu-ttl">My Menu</p>
                        <div class="member__myMenu-item mt-5">
                            <a href="profEdit.php" class="txt-14">プロフィール編集</a>
                        </div>
                        <p class="member__myMenu-ttl mt-20">会話中の旅トモ</p>
                        <ul class="mt-5">
                            <li class="member__myMenu-item"><a href="" class="txt-14">旅トモ1</a></li>
                            <li class="member__myMenu-item"><a href="" class="txt-14">旅トモ2</a></li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="member__list">
                <?php foreach($dbSearchData as $key => $val): ?>
                <div class="member__item" style="<?= ($val['id'] === $_SESSION['user_id']) ? 'display:none;' : ''; ?>">
                    <p class="member__img"><img src="<?= $val['pic']; ?>" alt=""></p>
                    <div class="member__txt">
                        <p>名前：<?= $val['name']; ?></p>
                        <p>性別：<?= $val['sex']; ?></p>
                        <p>滞在先：<?= getCountry($val['country']); ?></p>
                        <p>目的：<?= getPurpose($val['purpose']); ?></p>
                        <div class="txt-center">
                            <a href="profDetail.php<?= '?u_id='.$val['id']; ?>" class="member__btn mt-10">詳細を見る</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($dbSearchData)): ?>
                    <p>検索結果がありませんでした。</p>
                <?php endif; ?>
                <div class="member__item">
                    <p class="member__img"><img src="img/mypic.jpg" alt=""></p>
                    <div class="member__txt">
                        <p>名前：五十嵐稔</p>
                        <p>性別：男</p>
                        <p>滞在先：ベトナム</p>
                        <p>目的：お話したい</p>
                        <div class="txt-center">
                            <a href="#" class="member__btn mt-10">詳細を見る</a>
                        </div>
                    </div>
                </div>
                <div class="member__item">
                    <p class="member__img"><img src="img/mypic.jpg" alt=""></p>
                    <div class="member__txt">
                        <p>名前：五十嵐稔</p>
                        <p>性別：男</p>
                        <p>滞在先：ベトナム</p>
                        <p>目的：お話したい</p>
                        <div class="txt-center">
                            <a href="#" class="member__btn mt-10">詳細を見る</a>
                        </div>
                    </div>
                </div>
                <div class="member__item">
                    <p class="member__img"><img src="img/mypic.jpg" alt=""></p>
                    <div class="member__txt">
                        <p>名前：五十嵐稔</p>
                        <p>性別：男</p>
                        <p>滞在先：ベトナム</p>
                        <p>目的：お話したい</p>
                        <div class="txt-center">
                            <a href="#" class="member__btn mt-10">詳細を見る</a>
                        </div>
                    </div>
                </div>
                <div class="member__item">
                    <p class="member__img"><img src="img/mypic.jpg" alt=""></p>
                    <div class="member__txt">
                        <p>名前：五十嵐稔</p>
                        <p>性別：男</p>
                        <p>滞在先：ベトナム</p>
                        <p>目的：お話したい</p>
                        <div class="txt-center">
                            <a href="#" class="member__btn mt-10">詳細を見る</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</body>
<?php require('footer.php'); ?>