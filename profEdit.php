<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================
// DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($dbFormData,true));

//目的データ取得
$purposes = getPurposeList();
//滞在地情報取得
$countries = getCountryList();


//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));

    //変数にユーザー情報を格納
    //画像をアップロードし、パスを格納
    $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic') : '';
    // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
    $pic = (empty($pic) && !empty($dbFormData['pic'])) ? $dbFormData['pic'] : $pic;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $country = $_POST['country'];
    $purpose = $_POST['purpose'];
    $greeting = $_POST['greeting'];
    
    //DBの情報と入力情報が異なる場合にバリデーションをおこなる
    if($dbFormData['name'] !== $name){
        //名前の最大文字数をチェック
        validMaxLen($name,'name');
    }
    if($dbFormData['email'] !== $email){
        validMaxLen($email,'email');
        //emailの形式チェック
        validEmail($email,'email');
        //emailの未入力チェック
        validRequired($email,'email');
        if(empty($err_msg['email'])){
            validEmailDup($email);
        }
    }

    if(empty($err_msg)){
        debug('バリデーションOKです');

        //例外処理
        try {
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'UPDATE users SET name = :name, email = :email, age = :age, sex = :sex, country = :country, purpose = :purpose, greeting = :greeting, pic = :pic WHERE id = :u_id';
            $data = array(':name' => $name, ':email' => $email, ':age' => $age, ':sex' => $sex, ':country' => $country, ':purpose' => $purpose, ':greeting' => $greeting, ':pic' => $pic, ':u_id' => $dbFormData['id']);
            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);

            //クエリ成功の場合
            if($stmt){
                $_SESSION['msg_success'] = SUC02;
                debug('マイページへ遷移します。');
                header('Location:index.php');//トップページへ
            }
        } catch (Exception $e){
            error_log('エラー発生：'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

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
                <form action="" method="post" class="mypage__form" enctype="multipart/form-data">
                    <div>
                        プロフィール画像
                        <label for="pic" class="area-drop">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                            <input type="file" id="pic" name="pic" class="input-file">
                            <img src="<?= getFormData('pic') ?>" alt="" style="<?= (empty(getFormData('pic'))) ? 'display:none;' : ''; ?>" class="prev-img">
                            ドラッグ&ドロップ
                        </label>
                    </div>
                    <div class="mt-20">
                        <label for="name">名前</label>
                        <input type="text" id="name" name="name" value="<?= getFormData('name'); ?>">
                    </div>
                    <div class="mt-20">
                        <label for="email">Eメール</label>
                        <input type="text" id="email" name="email" value="<?= getFormData('email'); ?>">
                    </div>
                    <div class="mt-20 w-20">
                        <label for="age">年齢</label>
                        <input type="text" id="age" name="age" value="<?= getFormData('age'); ?>">
                    </div>
                    <div class="mt-20 w-20">
                        <label for="sex">性別</label>
                        <select name="sex" id="sex">
                            <option value="noselect" <?= ($dbFormData['sex'] == 'noselect') ? 'selected' : ''; ?>>未選択</option>
                            <option value="男" <?= ($dbFormData['sex'] == '男') ? 'selected' : ''; ?>>男</option>
                            <option value="女" <?= ($dbFormData['sex'] == '女') ? 'selected' : ''; ?>>女</option>
                        </select>
                    </div>
                    <div class="mt-20">
                        <label for="coutry">現在の滞在地</label>
                        <select name="country" id="country">
                            <option value="0" <?= (getFormData('country') == 0) ? 'selected' : 0; ?> >---選択してください---</option>
                            <?php foreach($countries as $key => $val): ?>
                            <option value="<?= $val['id']; ?>" <?= (getFormData('country') == $val['id']) ? 'selected' : ''; ?>><?= $val['country_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mt-20">
                        <label for="purpose">目的</label>
                        <select name="purpose" id="purpose">
                            <option value="0" <?= (getFormData('purpose') == 0) ? 'selected' : ''; ?>>---選択してください---</option>
                            <?php foreach($purposes as $key => $val): ?>
                                <option value="<?= $val['id']; ?>" <?= (getFormData('purpose') == $val['id']) ? 'selected' : ''; ?> ><?= $val['purpose']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mt-20">
                        <label for="greeting">自己紹介</label>
                        <textarea name="greeting" id="" cols="20" rows="5" name="greeting"><?= getFormData('greeting'); ?></textarea>
                    </div>
                    <div class="mt-30">
                        <input type="submit" value="登録する" class="form-btn">
                    </div>
                </form>
                <div class="mypage__list">
                    <ul class="list-items">
                        <li><a href="passEdit.php">パスワード変更</a></li>
                        <li><a href="withdraw.php">退会</a></li>
                        <li><a href="index.php">トップページへ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</body>
<?php require('footer.php'); ?>
</html>