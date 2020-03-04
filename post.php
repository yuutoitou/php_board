<?php
include('sub.php');
if(empty($_SESSION['id'])){
  header("location: login.php");
}
if(!empty($_POST['submit'])){
post_date();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>投稿ページ</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width"> 
  <link href="style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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
        <h1>投稿する</h1>
        <!--入力エラーの場合エラーを表示する-->
        <b class="err_message"><?=$err?></b>
        <form class="post_form" action="" method="post" enctype="multipart/form-data">
          <textarea name="textarea" class="textarea" cols="63" rows="13" maxlength="200"></textarea>
          <p style="margin:0;text-align: right;"><span id="js_count_up">0</span>/200</p>
          <div class="post_button">
            <div class="post_img">
              <label class="preview">
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304">
                <input id="up_img" style="display:none;" type="file" name="img_file" accept="image/*">
              </label>
            </div>  
            <input style="margin-left:10px;" type="submit" id="input_button" class="login_buton" value="投稿" name="submit">
          </div>  
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