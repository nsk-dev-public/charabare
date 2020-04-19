<?php

	if (empty($_POST['nickname']) 
		|| empty($_POST['age']) 
		|| empty($_POST['sex']) 
		|| empty($_POST['height'])) {
		// どれか空だったら戻す
		header('Location: /edit/');
		exit;
	}


?>
<?php include(dirname(__FILE__).'/../parts/firebase.php'); ?>
<script>
	async function regist (){
		let db = firebase.firestore();
		let ref = await db.collection('user_m').add({
		    nickname:<?=json_encode($_POST['nickname'])?>,
		    age:<?=json_encode($_POST['age'])?>,
		    sex:<?=json_encode($_POST['sex'])?>,
		    height:<?=json_encode($_POST['height'])?>
		});
	    if (!ref.id) {
	    	// 失敗してるのでリダイレクト
	    	
	    }else{
	    	document.cookie = 'charabare_id='+ref.id+'; path=/';
	    	location.href = '/talk/';
	    }
	};

regist();

</script>