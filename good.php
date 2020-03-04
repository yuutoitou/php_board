<?php
include('sub.php');
function good_button(){
  if(empty($_SESSION['id'])){
    echo "id_null";
    exit;
  }
  $user_name = $_SESSION['id'];
  $post_id = $_POST['id'];
  $check = sql_fetch("SELECT * FROM good_check WHERE id = '$post_id' AND user_name = '$user_name'");
  if(!$check){
    sql_execute("INSERT good_check (id,user_name) VALUES ('$post_id','$user_name')");
    sql_execute("UPDATE board SET good = good + 1 WHERE id = '$post_id'");
    echo "1";
    echo sql_fetch("SELECT * FROM board WHERE id = '$post_id'")['good'];
  } else {
     sql_execute("DELETE FROM good_check WHERE user_name = '$user_name'");
     sql_execute("UPDATE board SET good = good - 1 WHERE id = '$post_id'");
    echo "0";
    echo sql_fetch("SELECT * FROM board WHERE id = '$post_id'")['good'];
  }
}
good_button();
?>