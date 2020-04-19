<?php
	// cookieがあるか確認。なければeditに飛ばす
	$id = $_COOKIE['charabare_id'];
	if (empty($id)) {
		header('Location: /edit/');
		exit;
	}
?>

<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
	<?php include(dirname(__FILE__).'/../parts/include.php'); ?>
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
	<?php  include(dirname(__FILE__).'/../parts/firebase.php'); ?>
		
	<!-- メインコンテナ -->
	<div class="main_container">
		<div class="header">
			トーク
		</div>
		<div class="separator"></div>
		<!-- ボタン -->
		<div class="setting_button_area">
			<a href="/">
				<span class="exit_button">
					トークをやめる
				</span>
			</a>
			<a href="#">
				<span class="change_button">
					相手をチェンジ
				</span>
			</a>
		</div>
		<!-- End ボタン -->
		<!-- チャットコンテナ -->
		<div class="msg-container">
			<div class="msg-content" id="output">
				<!-- <div class="msg msg-send">今日午後新しくできたカフェに行かない？？</div> -->
				<!-- <div class="msg msg-receive">いいよ！　何時に待ち合わせ？</div> -->
				<!-- <div class="msg msg-system">トークが開始されました</div> -->
			</div>
		</div>
		<!-- End チャットコンテナ -->
		<textarea id="message" class="message_input" placeholder="メッセージを入力してください"></textarea>
		<div class="send_button">
			<button id="send" class="circle"><img src="/img/btn_send.png"></button>
		</div>
	</div>
	<!-- End メインコンテナ -->
	<!-- modal -->
	<div class="modal js-modal">
        <div class="modal__bg"></div>
        <!-- ポップアップ -->
        <div class="modal__content">
            <div class="js-modal-close"><p>相手を探す</p></div>
        </div>
        <!-- インジゲーター -->
		<div class="spinner">
			<div class="double-bounce1"></div>
			<div class="double-bounce2"></div>
		</div>
    </div>
    <!-- End modal -->
	<script>
		// $('#add').on('click', function() {
		//   // 一番下までスクロールする
		$(function(){
			$('.msg-container').animate({scrollTop: $('.msg-container')[0].scrollHeight}, 'fast');
		});
		// });
		$(function(){
		    $('.js-modal-open').on('click',function(){
		        $('.js-modal').fadeIn();
		        return false;
		    });
		    var output = document.getElementById("output");
		    $('.js-modal-close').on('click',async function(){
		        $('.modal__content').fadeOut();
		        // まずロード出す
		        $('.spinner').fadeIn();

				// マッチング待ち状態にする
				let db = firebase.firestore();
				let ref = await db.collection('match_t').add({
			        user_id:<?= json_encode($id); ?>,
			        status:0,
			        room_id:""
				});
				// マッチング状態の監視
				let roomId = "";
				let unsubscribe = db.collection('match_t').doc(ref.id)
					.onSnapshot(function(doc){
					console.log(doc.data());
					// マッチングキタコレ
					if (doc.data().status == 1) {
						roomId = doc.data().room_id;
						unsubscribe(); // リスナー止める
						// 部屋入る
				        setTimeout(function(){
					        $('.js-modal').fadeOut();
					        output.innerHTML += '<div class="msg msg-system">トークが開始されました</div>';
						}, 2000);
		        		// チャット
					    // 送信処理
					    var send = document.getElementById("send");
					    var message = document.getElementById("message");
					    send.addEventListener('click', function() {
						    db.collection("room_t").doc(roomId).collection('message').add({
						        user_id:<?= json_encode($id); ?>,
						        text:message.value
						    });
						    message.value="";
						    name.value="";
						});
					    // 受信処理
					    db.collection("room_t/"+roomId+"/message/")
					    	.onSnapshot(function(snapshot){
					    	if (snapshot.docs.length <= 0) { return 0; }
				    		const v = snapshot.docs[snapshot.docs.length -1].data();
				    		snapshot = null; // メモリ解放
				    		if (v != null) {
						    	// 先に前のメッセージのアニメ消す
						    	$(".msg").removeClass("msg");
						    	// 取得処理
							    let str = "";
							    if (v.user_id == <?= json_encode($id); ?>) {
							    	// 自分の発言
								    str += '<div class="msg msg-send">'+v.text+'</div>';
							    }else{
							    	// 相手の発言
								    str += '<div class="msg msg-receive">'+v.text+'</div>';
							    }
							    output.innerHTML += str;
								$('.msg-container').animate({scrollTop: $('.msg-container')[0].scrollHeight}, 'fast');
				    		}
						});

					}
				});
				console.log(roomId);


		        return false;
		    });
		});

	</script>

</body>
</html>