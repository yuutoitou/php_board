<?php
include('sub.php');

if(empty($_SESSION['id'])){
  header("location: login.php");
}

$profile_date = profile();

if(!empty($_POST['profile_submit'])){
  user_profile();
}
if(!empty($_POST['pass_submit'])){
  pass_reset();
}
if(!empty($_POST['withdraw_submit'])){
  withdraw();
}
if(!empty($_POST['cancel_submit'])){
  
  header("location:profile.php?u_id={$_SESSION['id']}");
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>プロフィールページ</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <link href="style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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
    <div class="profile_container">
      <aside id="profile">
        <div>
          <div class="profile_img">
            <img src="<?=$profile_date[0]?>">
            <p style="width:45%;"><?=$profile_date[1]?></p>
          </div><p class="text" style="width: 90%"><?=$profile_date[2]?></p>
        </div>
<?php
  if($_GET['u_id'] == $_SESSION['id']){
    profile_contents();
  }
?>        
      </aside>
      <article class="user_text">
        <p class="post_check"><a href="profile.php?profile=profile&<?="u_id=".$_GET['u_id']?>">投稿:<span><?=$profile_date[4]?></span></a> <a href="profile.php?profile=good&<?="u_id=".$_GET['u_id']?>">いいね:<span><?=$profile_date[5]?></span></a></p>
        <div id="text_contents">
          <div class="main_text">
          </div>
          <div class="good_text">
          </div>
<?php
  if(!empty($_GET['menu'])){
  switch($_GET['menu']){
      case 'withdraw_edit':
            withdraw_edit();
        break;
      case 'pass_edit':
         pass_edit();
        break;
      case 'profile_edit':
         profile_edit();
        break;
      }
   } else {
   
  }
  if(!empty($_GET['profile'])){ 
  if($_GET['profile'] == 'profile'){
    index_profile();
  } elseif($_GET['profile'] == 'good'){
    index_good(); 
   }
  }
?>

        </div>
      </article>
    </div>  
  </div>
  <footer>
    <p><a href="">© 2019 ECOna</a></p>
  </footer>  
  <script type="text/javascript" src="sub.js"></script>
  </body>
</html>
