<?php
include('sub.php');
if(!empty($_POST['submit'])){  
user_login();
}

$test_id = $test_pass = null;

if(!empty($_SESSION['test_id']) && !empty($_SESSION['test_pass'])){
  $test_id = $_SESSION['test_id'];
  $test_pass = $_SESSION['test_pass'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>ログインページ</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">  
  <link href="style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
  <header>
    <h1><a href="index.php">ECOna</a></h1>
    <!-- ナビメニュー部分 -->
    <nav class="pc_nav">
      <ul>
        <li><a href="new_user.php">新規登録</a></li>
        <li><a href="login.php">ログイン</a></li>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="post.php">投稿</a></li>
        <li><a href="profile.php?u_id=<?=$_SESSION['id']?>">ホーム</a></li>
      </ul>
    </nav>
　　<!-- ナビボタン -->
    <div class="Toggle">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <!-- スマホナビメニュー部分 -->
    <nav class="sp_nav">
      <ul>
        <li><a href="new_user.php">新規登録</a></li>
        <li><a href="login.php">ログイン</a></li>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="post.php">投稿</a></li>
        <li><a href="profile.php?u_id=<?=$_SESSION['id']?>">ホーム</a></li>
      </ul>
    </nav>    
  </header>
<p class="job" style="display:none;"><?=$job1?></p>  
  <div id="container">
    <article id="user">
      <div id="user_form">
        <h1>ログイン</h1>
        <!--入力エラーの場合エラーを表示する-->
        <b class="err_message"><?=$err?></b>
        <form action="" method="post">
          <p>会員ID：</p>
          <input type="text" name="id" placeholder="ご登録のIDを入力してください" value="<?=$test_id?>">
          <p>パスワード：</p>
          <input type="password" name="password" placeholder="ご登録のパスワードを入力してください" value="<?=$test_pass?>">
          <br>  
          <input type="submit" id="input_button" class="login_buton" value="ログイン" name="submit">
        </form>
        <b class="pass_reset_link"><?=$pass_reset?></b>
      </div>  
    </article>
  </div>
  <footer>
    <p><a href="">© 2019 ECOna</a></p>
  </footer>
<script type="text/javascript" src="sub.js"></script>
<?php $_SESSION['job1'] = null;?>
</body>  
</html>