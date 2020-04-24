<header>
    <div class="header inner">
        <div class="header__left">
            <div class="header__logo">
                <p class="header__ttl"><a href="#">旅トモ</a></p>
            </div>
        </div>
        <div class="header__right">
            <ul class="nav-menu">
                <?php if($_SERVER['PHP_SELF'] === '/login.php'){ ?>
                <li class="nav-menu__item"><a href="signup.php" class="nav-menu__btn">新規登録</a></li>
                <?php }elseif($_SERVER['PHP_SELF'] === '/signup.php'){ ?>
                <li class="nav-menu__item"><a href="login.php" class="nav-menu__btn">ログイン</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</header>