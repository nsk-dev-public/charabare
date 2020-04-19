<!DOCTYPE html>
<html>
<head>
	<?php include(dirname(__FILE__).'/../parts/include.php'); ?>
	<title></title>
</head>
<body>
	<?php // include(dirname(__FILE__).'/../parts/firebase.php'); ?>
	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>  
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-database.js"></script>

	<!-- TODO: Add SDKs for Firebase products that you want to use
	     https://firebase.google.com/docs/web/setup#available-libraries -->
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-analytics.js"></script>
	<script>
	  // Your web app's Firebase configuration
	  var firebaseConfig = {
	    apiKey: "xxx",
	    authDomain: "xxx",
	    databaseURL: "xxx",
	    projectId: "xxx",
	    storageBucket: "xxx",
	    messagingSenderId: "xxx",
	    appId: "xxx",
	    measurementId: "xxx"
	  };
	  // Initialize Firebase
	  firebase.initializeApp(firebaseConfig);
	  firebase.analytics();
	</script>

	<!-- ここまでfirebase -->
	<div class="main_container">
		<form action="/edit/regist.php" method="post">
			<div class="header">
				プロフィール設定
			</div>
			<!-- 入力エリア -->
			<div class="input_area">
				<div class="item_title">ニックネーム</div>
				<input class="item_input" type="text" name="nickname" placeholder="ニックネームを入力してください">
				<div class ="underline"></div>
			</div>
			<div class="input_area">
				<div class="item_title">年齢</div>
				<input class="item_input" type="text" name="age" placeholder="年齢を入力してください">
				<div class ="underline"></div>
			</div>
			<div class="input_area">
				<div class="item_title">性別</div>
				<input class="item_input" type="text" name="sex" placeholder="性別を入力してください">
				<div class ="underline"></div>
			</div>
			<div class="input_area">
				<div class="item_title">身長</div>
				<input class="item_input" type="text" name="height" placeholder="身長を入力してください">
				<div class ="underline"></div>
			</div>
			<div class="input_area">
				<div class="item_title">写真</div>
				<input class="img_input" type="file" name="img" placeholder="写真を入力してください">
				<div class ="underline"></div>
			</div>

			<div class="separator"></div>
			<!-- スタートボタン -->
			<!-- <a href="/talk/"> -->
				<div class="start_button_area">
					<input type="submit" value="トークをはじめる">
				</div>
			<!-- </a> -->
			<div class="start_caution">
				<h2>※注意事項※</h2>
				<p>プロフィールは本当の事を書かなくたっていいんだよ</p>
				<p>本当の自分が『ネタバレ』ないように上手に隠して遊んでね</p>
				<p class="space"></p>
				<p>一度登録したデータはCookieに保存されます。</p>
				<p>キャッシュをクリアしない限りは、そのまま継続して遊べます</p>
				<p>また、30日以上ログインがないデータはその場で削除されますので</p>
				<p>安心してご利用ください。</p>
				<p>このサービスは、出会い系ではございません。</p>
			</div>
			<!-- End スタートボタン -->
		</form>
		<div class="separator"></div>
		<?php include(dirname(__FILE__).'/../parts/footer.php'); ?>
	</div>
	<script>
	var database = firebase.database();
	let room = "chat_room";
	
	    database.ref(room).push({
	        name:"なまえ",
	        message:"メッセージ"
	    });

	</script>

</body>
</html>