//全画面用画面表示用

$(function(){
  h = $(window).height();
  form_h = $('#user').height()/2;
  $('#user').css('padding-top',(h - 70 - 70)/2 - form_h +'px');
  $('#container').css('min-height',h - 70 - 70 +'px');  
  //$('#new_user').css('margin-bottom',(h - 70 - 70)/2 - form_h +'px');
});

$(window).resize(function(){
  h = $(window).height();
  form_h = $('#user').height()/2;
  $('#user').css('padding-top',(h - 70 - 70)/2 - form_h +'px');
  $('#container').css('min-height',h - 70 - 70 +'px');  
});

//post.php txtearea 文字数カウント

$(function(){
  $('.textarea').bind('keydown keyup keypress change',function(){
  var this_length = $(this).val().length;
  var max_len = $(this).attr('maxlength')  
  $('#js_count_up').html(this_length);
  if(Number($('#js_count_up').html()) > max_len){
    $('#js_count_up').css('color','red');
  } else {
    $('#js_count_up').css('color','black');
  }
   
  });
});

//post.php 投稿画像 preview
$(function(){
  $('#up_img').on('change',function(e){
    $('.preview_img').remove();
    var file = e.target.files[0],
        reader = new FileReader();
    if(file.type.indexOf("image") < 0){
      return false;
    }

    reader.onload = (function() {
       $('.preview').append($('<img>').attr({
         src: reader.result,
         class: 'preview_img'
       }));                  
    });
    reader.readAsDataURL(file);
  });
});

//goodボタン処理
$(function(){
  $('.good').on('click',function(){
    var post_id = $(this).parents('article').find('.post_id').html();
    $.ajax({
      url:'good.php',
      type:'POST',
      data: {
        'id':post_id},
      beforeSend: function(xhr) {
        // sessionの引き継ぎ
      xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
      },
      success: function(result){      
    },                    
    error: function(){
      console.log( jqXHR, textStatus, errorThrown, arguments );
    }
        })
    .done((data) =>{
      if(data == "id_null"){
        window.location.href ="login.php";
        return;
      }
      var js_count = data.substr(0,1);      
      if(js_count == 1){
        $(this).parents('#user_meaadage').find('.good').removeClass('good_off');
        $(this).parents('#user_meaadage').find('.good').addClass('good_on');
        var good = data.replace(1,"");
      } else {
         $(this).parents('#user_meaadage').find('.good').removeClass('good_on');
         $(this).parents('#user_meaadage').find('.good').addClass('good_off');
         var good = data.replace(0,"");
       }
      $(this).parents('#user_meaadage').find('.good').html(good);      
    })
  })
});
 
//コメントopen
$(function(){
  $('.comment_b').on('click',function(){
    let open_check = $(this).parents('#user_meaadage').find('.board_coments');
    if(open_check.hasClass('board_coments_open')){
      open_check.removeClass('board_coments_open')
      $(this).html("コメントを見る");
      } else {
          open_check.addClass('board_coments_open');
          $(this).html("コメントを閉じる");
      }
  })
});

//jobコメント
$(function(){
  if($('.job').text().length > 0){
  $('.job').addClass('job_mes');
} else {
  $('.job').removeClass('job_mes');
}
  $('.job').slideDown(800);
  function action(){$('.job').slideUp(800);};
  
  setTimeout(action,1500);
});

//コメント編集用
$(function(){
  $now_img = $('.now_img').attr('src');
  
  if($now_img == ""){
    $('.now_img').css('display','none');
  }
  $('#up_img').on('change',function(){
    $('.now_img').remove();
  })
});

//プロフィール用
$(function(){
  $(function(){
  $('.profile_submit').on('click',function(){
    
    var img_file = new FormData($('#form_d').get(0));
    $.ajax({
      url:'sub.php',
      method:'post',
      dataType: "html",
      processData: false,
      contentType: false,        
      data:img_file
        }).done((data, textStatus, jqXHR) =>{
       $('.err_message').html(data);
    })
   })
  })
})

//ハンバーガーメニュー
$(function() {
　$('.Toggle').click(function() {
　　$(this).toggleClass('active');

　if ($(this).hasClass('active')) {
　　$('.Toggle').parent().find('.sp_nav').slideDown();
　} else {
　　$('.Toggle').parent().find('.sp_nav').slideUp();
　}
　});
});

//ハンバーガーメニューscroll
$(function(){
  $(window).on('load scroll',function(){
    if($(window).scrollTop() > 100){
      $('header').addClass('fixed');
    } else {
      $('header').removeClass('fixed');
    }
  })
});


