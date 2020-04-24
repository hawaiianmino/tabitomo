<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

?>

<?php require('head.php'); ?>
<body>
    <?php require('header.php'); ?>
    <!---------------
        3. mypage
    ---------------->    
    <section id="mypage">
        <div class="inner">
            <h1 class="main-ttl txt-center mt-20">プロフィール編集画面</h1>
            <div class="mypage mt-20">
                <form action="" method="post" class="mypage__form">
                    <div>
                        <label for="pic">プロフィール画像</label>
                        <input type="file" id="pic" name="pic">
                    </div>
                    <div class="mt-20">
                        <label for="name">名前</label>
                        <input type="text" id="name" name="name">
                    </div>
                    <div class="mt-20 w-20">
                        <label for="tel">年齢</label>
                        <input type="text" id="tel" name="tel">
                    </div>
                    <div class="mt-20 w-20">
                        <label for="sex">性別</label>
                        <select name="sex" id="sex">
                            <option value="man">男</option>
                            <option value="woman">女</option>
                        </select>
                    </div>
                    <div class="mt-20">
                        <label for="coutry">現在の滞在地</label>
                        <input type="text" id="country" name="country">
                    </div>
                    <div class="mt-20">
                        <label for="purpose">目的</label>
                        <select name="purpose" id="purpose">
                            <option value="1">食事したい</option>
                            <option value="1">話したい</option>
                            <option value="1">友達になりたい</option>
                        </select>
                    </div>
                    <div class="mt-20">
                        <label for="greeting">自己紹介</label>
                        <textarea name="greeting" id="" cols="20" rows="5" name="greeting"></textarea>
                    </div>
                    <div class="mt-30">
                        <input type="submit" value="登録する" class="form-btn">
                    </div>
                </form>
                <div class="mypage__list">
                    <ul class="list-items">
                        <li><a href="">パスワード変更</a></li>
                        <li><a href="">退会</a></li>
                        <li><a href="">マイページトップ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</body>
</html>