<?php
include('sub.php');
if(!empty($_GET['test_user'])){
  test_user();
}

$test_link = null;

if(empty($_SESSION['id'])){
  $test_link = '<a href="index.php?test_user=start" class="test_login"><span>テストログインしてみる</span></a>';
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>ECOnaトップページ</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@nekonohito">
  <meta property="og:url" content="https://www.econa-life.com/index.php" /> <!--③-->
  <meta property="og:title" content="Ecoな掲示板" /> <!--④-->
  <meta property="og:description" content="ポートフォリオ用の掲示板アプリです。phpで作成しました。" /> <!--⑤-->
<meta property="og:image" content="https://www.econa-life.com/upload/images.jpg" /> <!--⑥-->
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
    <p class="job" style="display:none;"><?=$job1?></p>
  <?=$test_link?>
  <div id="container" style="max-width:98%;">
    <article id="user_meaadage" style="margin-top:10px;">
      <div class="user_name">
        <div class="user_box">
          <img src="upload/images%20(1).jpg">
        </div>  
        <p class="index_p">Ecoな猫</p>
      </div>
      <div class="text_contents">
        <p>このWebサイトはエコな取り組みをなにかとアピールする掲示板アプリです。と言いつつもポートフォリオ用なのでご自由にお使いください。</p>
        <img style="max-width:100%;" src="upload/images.jpg">
      </div>
    </article>
    <?php
    index_main();?>
  </div>
  <footer>
    <p><a href="">© 2019 ECOna</a></p>
  </footer>
<script type="text/javascript" src="sub.js"></script>
<?php $_SESSION['job1'] = null;?>  
</body>  
</html>