<?php
include('sub.php');
if(!empty($_POST['submit'])){
  new_user();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>新規登録ページ</title>
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
  <div id="container">
    <article id="user">
      <div id="user_form">
        <h1>新規会員登録</h1>
        <!--入力エラーの場合エラーを表示する-->
        <b class="err_message"><?=$err?></b>
        <form action="" method="post">
          <p>会員ID：</p>
          <input type="text" name="id" placeholder="半角英数8~16文字で登録してください">
          <p>パスワード：</p>
          <input type="password" name="password" placeholder="半角英数8~16文字で登録してください">
          <p>メールアドレス：</p>
          <input type="text" name="address" placeholder="メールアドレスを入力してください">
          <br>  
          <input type="submit" id="input_button" value="登 録" name="submit">
        </form>
      </div>  
    </article>
  </div>
  <footer>
    <p><a href="">© 2019 ECOna</a></p>
  </footer>
<script type="text/javascript" src="sub.js"></script>  
</body>  
</html>