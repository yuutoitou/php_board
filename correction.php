<?php
include('sub.php');
$id = $_POST['id'];
$row = sql_fetch("SELECT * FROM board WHERE id = '$id'");
if(!empty($_POST['submit_c'])||!empty($_POST['submit_d'])){
  correction();
}
$_SESSION['post_id'] = $_POST['id'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>投稿内容を編集する</title>
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
        <h1>投稿を編集する</h1>
        <!--入力エラーの場合エラーを表示する-->
        <b class="err_message"><?=$err?></b>
        <form action="" method="post" enctype="multipart/form-data">
          <textarea name="text" class="textarea" cols="63" rows="13" maxlength="200"><?=$row['post']?></textarea>
          <p style="margin:0;text-align: right;"><span id="js_count_up">0</span>/200</p>
          <div class="post_button">
            <div class="post_img">
              <label class="preview">
                <img class="now_img" src="<?=$row['img']?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304">
                <input id="up_img" style="display:none;" type="file" name="img_file" accept="image/*">
              </label>
            </div>
            <div>
              <input style="width:100px;background-color:#800000"; type="submit" id="input_button" class="login_buton" value="削除" name="submit_d">
              <input style="width:100px;" type="submit" id="input_button" class="login_buton" value="編集" name="submit_c">
            </div>  
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