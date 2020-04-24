<?php

//================================
// ログ
//================================
//ログを出力
ini_set('log_errors','on');
//ログの出力先を指定
ini_set('error_log','php_log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debag_flg = true;
//デバッグログ関数
function debug($str){
    global $debag_flg;
    if(!empty($debag_flg)){
        error_log('デバッグ：'.$str);
    }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime',60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime',60*60*24*30);
//セッションを使う
session_start();


//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
    debug('セッションID:'.session_id());
    debug('セッション変数の中身:'.print_r($_SESSION,true));
    debug('現在日時のタイムスタンプ:'.time());
    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
        debug('ログイン期限日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}


//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG10', '電話番号の形式が違います');
define('MSG11', '郵便番号の形式が違います');
define('MSG12', '古いパスワードが違います');
define('MSG13', '古いパスワードと同じです');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('MSG17', '半角数字のみご利用いただけます');
define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');

//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$err_msg = array();

//================================
// バリデーション関数
//================================
//バリデーション関数（未入力チェック）
function validRequired($str,$key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
//バリデーション関数（Email形式チェック）
function validEmail($str,$key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
//バリデーション関数（同値チェック）
function validMatch($str1,$str2,$key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
//バリデーション関数（最小文字数チェック）
function validMinLen($str,$key,$min = 6){
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
//バリデーション関数（最大文字数チェック）
function validMaxLen($str,$key,$max = 256){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
//バリデーション関数（半角チェック）
function validHalf($str,$key){
    if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
//電話番号形式チェック
function validTel($str,$key){
    if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}
//郵便番号形式チェック
function validZip($str,$key){
    if(!preg_match("/^\d{7}$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG11;
    }
}
//半角数字チェック
function validNumber($str,$key){
    if(!preg_match("/^[0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG17;
    }
}
//固定長チェック
function validLength($str,$key,$len = 8){
    if(mb_strlen($str) !== $len){
        global $err_msg;
        $err_msg[$key] = $len.MSG14;
    }
}
//パスワードチェック
function validPass($str,$key){
    //半角英数字チェック
    validHalf($str,$key);
    //最大文字数チェック
    validMaxLen($str,$key);
    //最小文字数チェック
    validMinLen($str,$key);
}

//================================
// データベース
//================================
//DB接続関数
function dbConnect(){
    $dsn = 'mysql:dbname=tabitomo;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $option = array(
        //SQL実行失敗時にエラーコードのみ設定
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        //デフォルトフェッチモードを連想配列型式に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //バッファードクエリを使う
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    //PDOオブジェクト生成(DBヘ接続)
    $dbh = new PDO($dsn,$user,$password,$option);
    return $dbh;
}
function queryPost($dbh,$sql,$data){
    //クエリー作成
    $stmt = $dbh->prepare($sql);
    //プレースホルダーに値をセットし、SQL文を実行
    if(!$stmt->execute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL:'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
     }
     debug('クエリ成功');
     return $stmt;
}