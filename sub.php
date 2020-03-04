<?php
session_start();

//SQL変数
$user     = "econalife_user";
$password = "deepco2hide2002";
$dbname = 'econalife_database';
$host = 'mysql8086.xserver.jp';
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

//共有変数
$err = null;
$job1 = null;
$pass_reset = null;

if(!empty($_SESSION['job1'])){
  global $job1;
    $job1 = $_SESSION['job1'];
}

//エスケープ関数
function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//正規表現関数
function str_match($check,$str){
  if(preg_match($check,$str)){
    return true;
  } else {
    return false;
  }
}

//SQL関数
function sql_execute($SQL){
  global $dsn,$user,$password;
  try {
    $pdo = new PDO($dsn,$user,$password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = $SQL;
    $stm = $pdo->prepare($sql);  
    $stm->execute();
  } catch (exception $e) {
    return $e->getMessage();
    exit();
  } 
}

function sql_fetch($SQL){
  global $dsn,$user,$password;  
  try {
    $pdo = new PDO($dsn,$user,$password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = $SQL;
    $stm = $pdo->prepare($sql);  
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (exception $e) {
    return $e->getMessage();
    exit();
  } 
}

function sql_fetchall($SQL){
  global $dsn,$user,$password;  
  try {
    $pdo = new PDO($dsn,$user,$password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = $SQL;
    $stm = $pdo->prepare($sql);  
    $stm->execute();
    $result_all = $stm->fetchall(PDO::FETCH_ASSOC);
    return $result_all;
  } catch (exception $e) {
    return $e->getMessage();
    exit();
  } 
}

//新規登録用
function new_user(){
  global $err;
  if(empty($_POST['id'])){
    return $err = 'IDを入力してください';
  } else if(empty($_POST['password'])){
    return $err = 'パスワードを入力してください';
  } else if(empty($_POST['address'])){
    return $err = 'メールアドレスを入力してください';
  }
  
  if(!str_match('/\A[a-z\d]{8,16}+\z/i',$_POST['id'])){
    return $err = 'IDを半角英数字8~16文字で登録してください';
  } elseif(!str_match('/\A[a-z\d]{8,16}+\z/i',$_POST['password'])){
    return $err = 'パスワードを半角英数字8~16文字で登録してください';
  } elseif(!(bool)filter_var($_POST['address'], FILTER_VALIDATE_EMAIL)){
    return $err = 'メールアドレスを正しく入力してください';
  } else {
    //エスケープ関数
    $post_id = h($_POST['id']);
    $post_password = h($_POST['password']);
    $post_address = h($_POST['address']);
    $sql_check_id = sql_fetch("SELECT COUNT(*) FROM user_date WHERE id = '$post_id'")['COUNT(*)'];
    $sql_check_address = sql_fetch("SELECT COUNT(*) FROM user_date WHERE address = '$post_address'")['COUNT(*)'];
    if($sql_check_id > 0){
        return $err = 'IDが既に使用されています';
      } else if($sql_check_address > 0){
        return $err = 'メールアドレスが既に使用されています';
      } 
    sql_execute("INSERT user_date (id,password,address) VALUES ('$post_id','$post_password','$post_address')");
    }
  $_SESSION['job1'] = '新規登録が完了しました';
  header("location: login.php");
}

//ログイン用
function user_login(){
  global $err,$pass_reset;
  if(empty($_POST['id'])){
    return $err = 'IDを入力してください';
  } else if(empty($_POST['password'])){
    return $err = 'パスワードを入力してください';
  }

  //エスケープ関数
  $post_id = h($_POST['id']);
  $post_password = h($_POST['password']);
  $sql_check = sql_fetch("SELECT * FROM user_date WHERE id = '$post_id' AND password = '$post_password'");
  if($sql_check){
    $_SESSION['id'] = $sql_check['id'];
    $_SESSION['job1'] = "ログインが完了しました。";
    header("location: index.php");
    exit();
  } else {
    $err = 'IDまたはパスワードが間違っています';
    $pass_reset = '<a style="color:#0000ee;" href="password_mail.php">パスワードをお忘れですか？</a>';
    return array($err,$pass_reset);
  }
}

//パスワード再登録用（メール認証）
function password_reset_mail(){
  global $err;
  if(empty($_POST['address'])){
   return $err = 'メールアドレスを入力してください';
  } 
  
  if(!(bool)filter_var($_POST['address'], FILTER_VALIDATE_EMAIL)){
    return $err = 'メールアドレスを正しく入力してください';
  }
    
  $post_address = h($_POST['address']);
  $sql_check = sql_fetch("SELECT * FROM user_date WHERE address = '$post_address'");
  if(!$sql_check){
    return $err = 'ご入力頂いたメールアドレスは登録されていません';
  } else {
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    //認証コード
    $pass = md5(uniqid(rand(),true));
    //メール送信時間
    $time = date("Y-m-d H:i:s");
    //送信URL
    $url = "http://econa-life.com/password_reset.php?pass={$pass}";
    $to = $sql_check['address'];
    $title = "パスワード再設定を行ってください";
    $content = "お申込み頂いた「パスワード変更用URL」を送付致します。\n\nご本人様確認の為24時間以内にアクセス頂き、パスワードの再登録をお願い致します。\n\n※当メール送信後24時間を経過しますとセキュリティ保持の為、有効期限切れとなります。\n\nその際は再度、最初から手続きをお願い致します。\n\n{$url}";
    $header = "From:エコナ<rice.on.rice.on@gmail.com>";
    if(mb_send_mail($to, $title, $content,$header)){
      sql_execute("UPDATE user_date SET reset_id ='$pass',reset_time = '$time' WHERE address = '$to'");
      return $err = '認証メールを送信しました';
    } else { 
      return $err = 'メールの送信に失敗しました';
    }
  }
}  

//パスワード再登録
function password_reset(){
  global $err;
  if(empty($_POST['pass']) || empty($_POST['pass_re'])){
    return $err = "パスワードを入力してください。";
  } else {
    if($_POST['pass'] !== $_POST['pass_re']){
      return $err = "再確認用のパスワードを正しく入力してください。";
    }  
  }
  if(empty($_GET['pass'])){
    return $err = "認証コードの取得に失敗しました。<br>恐れ入りますが再度認証メールからアクセスしてください。";
  } else {
    $pass_code  = $_GET['pass'];
    //認証メール送信時間と現在時刻の比較
    $pass_time = new DateTime(sql_fetch("SELECT * FROM user_date WHERE reset_id = '$pass_code'")['reset_time']);
    $pass_time->modify('+24 hours');
    $time = new Datetime(date("Y-m-d H:i:s"));
    $diff = $time->diff($pass_time);
    if($diff->invert === 0) {
      $new_pass = h($_POST['pass']);
      sql_execute("UPDATE user_date SET password = '$new_pass'");
      return $err = 'パスワードを再登録しました';
    } else {
      return $err = '認証メール送信より24時間以上が経過しています。';
    }
  }
}

//投稿データ用
function post_date(){
  global $err;
  if(empty($_SESSION['id'])){
    header("locaciton: login.php");
  }
  if(!is_uploaded_file($_FILES['img_file']['tmp_name']) && empty($_POST['textarea'])){
    return $err = '画像かテキストを入力してください';
  }
  $a = null;
   
  //画像名と投稿日時に使用
  $today = date("YmdHis");
  //画像ファイル処理
  if(is_uploaded_file($_FILES['img_file']['tmp_name'])){
    if(!file_exists('upload')){
      mkdir('upload');
    }
    $a = 'upload/' . $today . basename($_FILES['img_file']['name']);
    move_uploaded_file($_FILES['img_file']['tmp_name'], $a);
  }
  //投稿データ処理
  
  if(!empty($_POST['textarea'])){
    $textarea = h($_POST['textarea']);
  }
  $name = $_SESSION['id'];
  $today = date("Y/m/d H:i:s");
  sql_execute("INSERT board (name,post,img,time) VALUES ('$name','$textarea','$a','$today')");
  $_SESSION['job1'] = '投稿しました。';
  header("location: index.php");
}

//profile情報
function profile(){
  $user_id = $_SESSION['id'];
  $user_date = sql_fetch("SELECT * FROM user_date WHERE id = '$user_id'");
  $img = $name = $message = $address = $board_count = $good_count = null;
  $board_count = sql_fetch("SELECT COUNT(*) FROM board WHERE name = '$user_id'")['COUNT(*)'];
  $good_count =  sql_fetch("SELECT COUNT(*) FROM good_check WHERE user_name = '$user_id'")['COUNT(*)'];
  if(!empty($_GET['u_id'])){
  if($_GET['u_id'] != $_SESSION['id']){
    $user_d = $_GET['u_id'];
    $board_count = sql_fetch("SELECT COUNT(*) FROM board WHERE name = '$user_d'")['COUNT(*)'];
    $good_count =  sql_fetch("SELECT COUNT(*) FROM good_check WHERE user_name = '$user_d'")['COUNT(*)'];
    $user_date = sql_fetch("SELECT * FROM user_date WHERE id = '$user_d'");
   }
  }
 
  if($user_date['img'] !== null){
    $img = $user_date['img'];
  }
  if($user_date['id'] !== null){
    $name = $user_date['id'];
  }
  if($user_date['message'] !== null){
    $message = $user_date['message'];
  }
  if($user_date['address'] !== null){
    $address = $user_date['address'];
  }
  return array($img,$name,$message,$address,$board_count,$good_count);
}

//profile更新
function user_profile(){
  global $err,$profile_date;
  $user_name = $address = $profile = $a = null;
  $user_id = $_SESSION['id'];
  $a = $profile_date[0];
  if(!empty($_POST['name'])){
    $user_name = h($_POST['name']);
  } else {
    echo 'ユーザー名を入力してください';
  }
  if(!empty($_POST['address'])){
    $address = $_POST['address'];
  } else {
    return 'メールアドレスを入力してください';
  }
  if(!empty($_POST['textarea'])){
    $profile = h($_POST['textarea']);
  }
  if(!str_match('/\A[a-z\d]{8,16}+\z/i',$_POST['name'])){
    return $err = 'IDを半角英数字8~16文字で登録してください';
  } elseif(!(bool)filter_var($_POST['address'], FILTER_VALIDATE_EMAIL)){
    return $err = 'メールアドレスを正しく入力してください';
  }
  
  //画像名と投稿日時に使用
  $today = date("YmdHis");
  //画像ファイル処理
  if(is_uploaded_file($_FILES['img_file']['tmp_name'])){
    if(!file_exists('upload')){
      mkdir('upload');
    }
    $a = 'upload/' . $today . basename($_FILES['img_file']['name']);
    move_uploaded_file($_FILES['img_file']['tmp_name'], $a);
   }
  
  sql_execute("UPDATE user_date SET id = '$user_name',address = '$address',message = '$profile',img = '$a' WHERE id = '$user_id'");
  sql_execute("UPDATE board SET name = '$user_name' WHERE name = '$user_id'");
  sql_execute("UPDATE comments SET name = '$user_name' WHERE name = '$user_id'");
  sql_execute("UPDATE good_check SET user_name = '$user_name' WHERE user_name = '$user_id'");
  $_SESSION['id'] = $user_name;
  header("location:https://www.econa-life.com/profile.php?menu=profile_edit&u_id={$user_name}");
}

//プロフィール編集form
function profile_edit(){
  global $profile_date,$err;
 echo <<<EDO
    <div class="user_data">
    <article class="user">
      <div id="user_form">
        <h1 style="font-size:30px;">プロフィールを編集する</h1>
        <!--入力エラーの場合エラーを表示する-->
        <b class="err_message">$err</b>
        <form id="form_d" action="" method="post" enctype="multipart/form-data">
        <div class="post_img" style="background-color:transparent;margin:0 auto;">
          <label class="preview" style="margin:0 auto;display:block;">
            <img style="height:120px;width:120px;margin:0 auto;display:block;float:none;border-radius:50%;" class="now_img" src="{$profile_date[0]}">
            <input type="hidden" name="MAX_FILE_SIZE" value="4194304">
            <input id="up_img" style="display:none;" type="file" name="img_file" accept="image/*">
          </label>
        </div>
          <p>ユーザー名：</p>
          <input class="pro_name" type="text" name="name" value="{$profile_date[1]}">
          <p>メールアドレス：</p>
          <input class="pro_address" type="text" name="address" value="{$profile_date[3]}">
          <br>
          <p>メッセージ:</p>
          <textarea name="textarea" maxlength=100 class="textarea" style="height:200px;">{$profile_date[2]}</textarea>
          <p style="text-align:right;margin-top:0px;"><span id="js_count_up">0</span>/100</p>
          <br>
          <input type="submit" id="input_button" class="login_buton profile_submit" value="変更する" name="profile_submit">
        </form>
      </div>  
    </article>
  </div>
EDO;
  
}
function withdraw_edit(){
  global $err;
  echo <<<EDO
    <div class="withdraw">
      <article class="user">
        <div id="user_form">
          <h1>退会しますか？</h1>
          <!--入力エラーの場合エラーを表示する-->
          <b class="err_message">{$err}</b>
          <form action="" method="post">
            <input type="submit" id="input_button" class="login_buton" value="退会する" name="withdraw_submit">
            <input type="submit" id="input_button" class="login_buton" value="キャンセル" style="background-color:red;" name="cancel_submit">
          </form>
        </div>  
      </article>            
    </div>
EDO;
}
function pass_edit(){
  global $err;
  echo <<<EDO
      <div class="pass_reset">
    <article class="user">
      <div id="user_form">
        <h1 style="font-size:30px;">パスワードを変更する</h1>
        <!--入力エラーの場合エラーを表示する-->
        <b class="err_message">{$err}</b>
        <form action="" method="post">
          <p>現在のパスワード：</p>
          <input type="text" name="password">
          <p>新しいパスワード：</p>
          <input type="text" name="new_password">
          <br>  
          <input type="submit" id="input_button" class="login_buton" value="パスワード変更" name="pass_submit">
        </form>
      </div>  
    </article>            
  </div>
EDO;
  
}

function profile_contents(){
  if(!empty($_GET['u_id'])){
  $get = $_GET['u_id'];
  }
  echo <<<EDO
    <ul class="profile_list">
      <li><a href="profile.php?menu=profile_edit&u_id=$get">プロフィール編集</a></li>
        <li><a href="profile.php?menu=pass_edit&u_id=$get">パスワード変更</a></li>
      <li><a href="logout.php">ログアウト</a></li>
      <li><a href="profile.php?menu=withdraw_edit&u_id=$get">退会する</a></li>
    </ul>
EDO;
  
}

//プロフィール画面投稿データ表示
function index_profile(){
  $user_id = $_SESSION['id'];
  $post_all = sql_fetchall("SELECT * FROM board WHERE name = '$user_id' ORDER BY id DESC");
  if(!empty($_GET['u_id'])){
    if($_GET['u_id'] != $_SESSION['id']){
      $user_d = $_GET['u_id'];
      $post_all = sql_fetchall("SELECT * FROM board WHERE name = '$user_d' ORDER BY id DESC");
    }
  }
  if(!empty($_SESSION['id'])){
    $user_id = $_SESSION['id'];
  }
  foreach ($post_all as $row){
    good_check($row['id']);
    if($row['name'] === $user_id){
      $delete = 'delete';
    } else {
      $delete = null;
    }
    global $good;
    $user_name = $row['name'];
    $id = $row['id'];
    $post_user = sql_fetch("SELECT * FROM user_date WHERE id = '$user_name'");
    $comments_list = sql_fetchall("SELECT * FROM comments WHERE id = '$id'");
    echo <<<EDO
        <article id="user_meaadage">
          <div class="user_name">
            <div class="user_box">
              <img src="{$post_user['img']}">
              <p>{$row['name']}</p>
              <p class='post_id' style="display:none;">{$row['id']}</p>
            </div>  
            <time>{$row['time']}</time>
          </div>
          <div class="text_contents">
            <p>{$row['post']}</p>
            <img src="{$row['img']}">
          </div>
          <div class="good_contents">
            <label>
              <span class="{$delete}"></span>
              <form class="coments_from" action="correction.php" method="post">
                <input type="submit" value="{$row['id']}" name="id">
              </form>
            </label>          
           <div class="g_c">
              <p class="comment_b">コメントを見る</p>
              <label>
              <p class="coments_p"><span class="coments">{$row['comments']}</span>
              <form class="coments_from" action="comment.php" method="post">
                <input type="submit" value="{$row['id']}" name="id">
              </form>
              </label>
              </p>
              <p><span class="good {$good}">{$row['good']}</span></p>
            </div>  
        </div>
EDO;
    foreach($comments_list as $list){     
     echo <<<EDO
      <div class="board_coments">
        <div class="board_box"><p class="name">name: {$list['name']}</p><time>{$list['time']}</time></div>
        <p class="text">{$list['comment']}</p>
      </div>
EDO;
    }
    echo "</article>";
    
  }
}

//プロフィール画面goodデータ表示
function index_good(){
  $user_id = $_SESSION['id'];
  $good_count = sql_fetchall("SELECT * FROM good_check WHERE user_name = '$user_id' ORDER BY id DESC");
  if(!empty($_GET['u_id'])){
  if($_GET['u_id'] != $_SESSION['id']){
    $user_d = $_GET['u_id'];
      $good_count = sql_fetchall("SELECT * FROM good_check WHERE user_name = '$user_d' ORDER BY id DESC");
   }
  }
  foreach($good_count as $len){
  $post_all = sql_fetchall("SELECT * FROM board WHERE id = '{$len['id']}' ORDER BY id DESC");
    $user_id = null;
  if(!empty($_SESSION['id'])){
    $user_id = $_SESSION['id'];
  }
  foreach ($post_all as $row){
    good_check($row['id']);
    if($row['name'] === $user_id){
      $delete = 'delete';
    } else {
      $delete = null;
    }
    global $good;
    $user_name = $row['name'];
    $id = $row['id'];
    $post_user = sql_fetch("SELECT * FROM user_date WHERE id = '$user_name'");
    $comments_list = sql_fetchall("SELECT * FROM comments WHERE id = '$id'");
    echo <<<EDO
        <article id="user_meaadage">
          <div class="user_name">
            <div class="user_box">
              <img src="{$post_user['img']}">
              <p>{$row['name']}</p>
              <p class='post_id' style="display:none;">{$row['id']}</p>
            </div>  
            <time>{$row['time']}</time>
          </div>
          <div class="text_contents">
            <p>{$row['post']}</p>
            <img src="{$row['img']}">
          </div>
          <div class="good_contents">
            <label>
              <span class="{$delete}"></span>
              <form class="coments_from" action="correction.php" method="post">
                <input type="submit" value="{$row['id']}" name="id">
              </form>
            </label>          
           <div class="g_c">
              <p class="comment_b">コメントを見る</p>
              <label>
              <p class="coments_p"><span class="coments">{$row['comments']}</span>
              <form class="coments_from" action="comment.php" method="post">
                <input type="submit" value="{$row['id']}" name="id">
              </form>
              </label>
              </p>
              <p><span class="good {$good}">{$row['good']}</span></p>
            </div>  
        </div>
EDO;
    foreach($comments_list as $list){     
     echo <<<EDO
      <div class="board_coments">
        <div class="board_box"><p class="name">name: {$list['name']}</p><time>{$list['time']}</time></div>
        <p class="text">{$list['comment']}</p>
      </div>
EDO;
    }
    echo "</article>";
    
  }
 }
}

//パスワード変更
function pass_reset(){
  global $err;
  $user_id = $_SESSION['id'];
  $password = sql_fetch("SELECT * FROM user_date WHERE id = '$user_id'")['password'];
  if(empty($_POST['password'])){
    return $err = '現在のパスワードを入力してください';
  } elseif (empty($_POST['new_password'])){
    return $err = '新しいパスワードを入力してください。';
  }
  $now_password = h($_POST['password']);
  if($password === $now_password){
    $new_pass = h($_POST['new_password']);
    sql_execute("UPDATE user_date SET password = '$new_pass' WHERE id = '$user_id'");
  } else {
    return $err = '現在のパスワードが間違っています';
  }
}

//退会処理
function withdraw(){
  $user_id = $_SESSION['id'];
  sql_execute("DELETE FROM user_date WHERE id = '$user_id'");
  header("location: index.php");
}

//good.css表示用
function good_check($post_id){
  global $row,$good;
  if(!empty($_SESSION['id'])){
    $user_id = $_SESSION['id']; 
    $id = $post_id;
    $check = sql_fetch("SELECT * FROM good_check WHERE id = '$id' AND user_name = '$user_id'");
    if($check){
      return $good = 'good_on';
    }
  }
  return $good = 'good_off';
};

//index.php 投稿表示
function index_main(){
  $post_all = sql_fetchall("SELECT * FROM board ORDER BY id DESC");
    $user_id = null;
  if(!empty($_SESSION['id'])){
    $user_id = $_SESSION['id'];
  }
  foreach ($post_all as $row){
    good_check($row['id']);
    if($row['name'] === $user_id){
      $delete = 'delete';
    } else {
      $delete = null;
    }
    global $good;
    $user_name = $row['name'];
    $id = $row['id'];
    $post_user = sql_fetch("SELECT * FROM user_date WHERE id = '$user_name'");
    $comments_list = sql_fetchall("SELECT * FROM comments WHERE id = '$id'");
    if($row['comments'] > 0){
      $com = '<p class="comment_b">コメントを見る</p>';
    } else {
      $com = "";
    }
    
    echo <<<EDO
        <article id="user_meaadage">
          <div class="user_name">
            <div class="user_box">
              <a href="profile.php?u_id={$row['name']}">
              <img src="{$post_user['img']}">
              </a>
            </div>
            <div class="user_box2">
              <a style='color:#000;' href="profile.php?u_id={$row['name']}">
              <p>{$row['name']}</p>
              </a>
              <p class='post_id' style="display:none;">{$row['id']}</p>
              <time>{$row['time']}</time>
            </div>  
          </div>
          <div class="text_contents">
            <p>{$row['post']}</p>
            <img src="{$row['img']}">
          </div>
          <div class="good_contents">
            <label>
              <span class="{$delete}"></span>
              <form class="coments_from" action="correction.php" method="post">
                <input type="submit" value="{$row['id']}" name="id">
              </form>
            </label>          
           <div class="g_c">
              {$com}     
              <label>
              <p class="coments_p"><span class="coments">{$row['comments']}</span>
              <form class="coments_from" action="comment.php" method="post">
                <input type="submit" value="{$row['id']}" name="id">
              </form>
              </label>
              </p>
              <p><span class="good {$good}">{$row['good']}</span></p>
            </div>  
        </div>
EDO;
    foreach($comments_list as $list){     
     echo <<<EDO
      <div class="board_coments">
        <div class="board_box"><p class="name">name: {$list['name']}</p><time>{$list['time']}</time></div>
        <p class="text">{$list['comment']}</p>
      </div>
EDO;
    }
    echo "</article>";
    
  }
}

//コメント入力
function comments(){
  global $err;
  if(empty($_POST['comment'])){
    return $err = 'コメントを入力してください';
  } 
  
  $user_id = $_SESSION['id'];
  $id = $_SESSION['post_id'];
  $comment = h($_POST['comment']);
  $today = date("Y/m/d H:i:s");
  sql_execute("INSERT comments (id,name,comment,time) VALUES ('$id','$user_id','$comment','$today')");    
  sql_execute("UPDATE board SET comments = comments + 1 WHERE id = '$id'");
  $_SESSION['job1'] = 'コメントしました';
  header("location: index.php");
}

//投稿編集用
function correction(){
  $id = $_SESSION['post_id'];
  $row = sql_fetch("SELECT * FROM board WHERE id = '$id'");
  $a = $row['img'];
  if(is_uploaded_file($_FILES['img_file']['tmp_name'])){
    sql_execute("UPDATE board SET img = ''");
  }
  if(!empty($_POST['submit_d'])){
    sql_execute("DELETE FROM board WHERE id = $id");
    sql_execute("DELETE FROM comments WHERE id = $id");
    sql_execute("DELETE FROM good_check WHERE id = $id");
    
    $_SESSION['job1'] = '投稿を削除しました';
    header("location: index.php");
  } else {
    $text = $_POST['text'];
    $img = $_POST['img'];
  if(is_uploaded_file($_FILES['img_file']['tmp_name'])){
    $a = 'upload/' . $today . basename($_FILES['img_file']['name']);
    move_uploaded_file($_FILES['img_file']['tmp_name'], $a);
  }
    sql_execute("UPDATE board SET post = '$text',img = '$a' WHERE id = '$id'");
    $_SESSION['job1'] = '投稿を編集しました。';
    header("location: index.php");
  }
}

//テストユーザーログイン
function test_user(){
  //$_GET['test_user'] = 'start';
  $test_user= sql_fetch("SELECT COUNT(*) FROM user_date WHERE password = 'testuser'")['COUNT(*)'];
  $test_user = 'testuser'. $test_user;
  $test_user_address = $test_user.'@yahoo.co.jp';
  sql_execute("INSERT user_date (id,password,address) VALUES ('$test_user','testuser','$test_user_address')");
  $_SESSION['test_id'] = $test_user;
  $_SESSION['test_pass'] = 'testuser';
  header('location:login.php');
}

?>