<?php require('head.php'); ?>
<?php require('header.php'); ?>
<body>
    <section id="profile" class="profile">
        <div class="inner">
            <h2 class="txt-28 txt-center">プロフィール詳細</h2>
            <div class="profile__wrap mt-20">
                <div class="profile__item">
                    <img src="img/travel.jpg" alt="">
                </div>
                <div class="profile__item">
                    <div>
                        <h3>名前：五十嵐稔</h3>
                    </div>
                    <div class="mt-10">
                        <h3>性別：男</h3>
                    </div>
                    <div class="mt-10">
                        <h3>滞在地：バンコク</h3>
                    </div>
                    <div class="mt-10">
                        <h3>目的：話したい</h3>
                    </div>
                    <div class="mt-20">
                        <h3>自己紹介：</h3>
                        <p>ここに自己紹介の文章が入ります。こに自己紹介の文章が入ります。こに自己紹介の文章が入ります。ここに自己紹介の文章が入ります。こに自己紹介の文章が入ります。こに自己紹介の文章が入ります。ここに自己紹介の文章が入ります。こに自己紹介の文章が入ります。こに自己紹介の文章が入ります。ここに自己紹介の文章が入ります。こに自己紹介の文章が入ります。こに自己紹介の文章が入ります。ここに自己紹介の文章が入ります。こに自己紹介の文章が入ります。こに自己紹介の文章が入ります。</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="chat" class="chat">
        <h2 class="txt-28 txt-center">メッセージ</h2>
        <div class="inner mt-20">
            <div class="chat__member2">
                <div class="chat__img2"><img src="img/travel.jpg" alt=""></div>
                <p class="chat__txt2">ここに相手の会話の文章が入ります。ここに相手の会話の文章が入ります。ここに相手の会話の文章が入ります。ここに相手の会話の文章が入ります。</p>
            </div>
            <div class="chat__member1 mt-20">
                <div class="chat__img1"><img src="img/mypic.jpg" alt=""></div>
                <p class="chat__txt1">ここに会話の文章が入ります。ここに会話の文章が入ります。</p>
            </div>
            <form action="#" method="post" class="chat__msg mt-20">
                <div class="chat__input">
                    <textarea name="message" id="message" cols="30" rows="5"></textarea>
                    <div class="txt-right mt-10">
                        <input type="submit" value="送信する" class="chat__btn">
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>
<?php require('footer.php'); ?>