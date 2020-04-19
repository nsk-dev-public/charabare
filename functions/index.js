const functions = require('firebase-functions');
const admin = require('firebase-admin');
admin.initializeApp(
	// {
	//   credential: admin.credential.applicationDefault(),
	//   databaseURL: 'https://xxx.firebaseio.com/'
	// }
);
const db = admin.firestore();

exports.monitorMatchng = functions.firestore.document('match_t/{matchId}')
	.onCreate((snap, context) => {
	// トランザクション開始
	return db.runTransaction(async function(t) {
		// 他に待機ユーザーがいないか確認する
		let query = db.collection('match_t').where('status', '==', 0);
		return await t.get(query).then(async function(querySnapshot) {
			// 2人以上いる場合はマッチング処理に入る
			if (querySnapshot.size >= 2) {
				let counter = 0;
				let userList = {};
		        await querySnapshot.forEach(function(doc) {
		        	counter = counter + 1;
		        	// データを格納する
	        		if (counter == 1) {
		        		userList.user_1 = doc;
	        		}else{
		        		userList.user_2 = doc;
	        		}
	        		// 2人集まったらマッチング
		        	if (counter >= 2) {
		        		// 部屋作る
						let c = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
						let cl = c.length;
						let roomKey = "";
						for(let i=0; i<32; i++){ roomKey += c[Math.floor(Math.random()*cl)]; }
		        		let roomRef = db.doc('room_t/'+roomKey);
		        		t.set(roomRef, {num:0});
		        		// room_idを二人のmatch_tに追加してあげる
		        		let user1Ref = db.doc('match_t/'+userList.user_1.id);
		        		let user2Ref = db.doc('match_t/'+userList.user_2.id);
		        		t.update(user1Ref, {status:1,room_id:roomKey});
		        		t.update(user2Ref, {status:1,room_id:roomKey});
		        		// カウンター戻しておく
		        		counter = 0;
		        	}
		        });
			}
	    });
	}).then(function() {
	    console.log("Transaction successfully committed!");
	}).catch(function(error) {
	    console.log("Transaction failed: ", error);
	});

    return 0;
});


// exports.makeUppercase = functions.database.ref('/match_t/user_list')
//     .onUpdate(async (change, context) => {
// 		const after = change.after.val();
// 		// 他に待ちユーザーがいないかを確認する
// 		    let waitUserList = [];
// 			for (const key in after) {
// 				if (after[key] == "wait") {
// 					waitUserList.push(key);
// 					if (waitUserList.length >= 2) {
// 						break;
// 					}
// 				}
// 			}
// 		    // 待ちユーザーがいたらstatusをtrueに
// 		    if (waitUserList.length >= 2) {
// 			    // room_tに新たな部屋を作ってroom_idを渡してあげる
// 			    const snapshot = await admin.database().ref('/room_t').push({
// 			        count:0
// 			    });
// 			    // room_idを入れる
// 				 change.after.ref.child(waitUserList[0]).transaction(async function (current_value) {
// 					if (current_value == "wait") {
// 						change.after.ref.child(waitUserList[1]).set(snapshot.key);
// 						return snapshot.key; 
// 					} else {
// 						return current_value; 
// 					}
// 				});

// 				// change.after.ref.child(waitUserList[1]).set(snapshot.key);
				
// 		    }
// 		    // その後2秒まってマッチデータを削除する
// 		 //    setTimeout(function(){
// 		 //        after[waitUserList[0]] = null;
// 		 //    	after[waitUserList[1]] = null;
// 			// }, 2000);
 

// 		// original.region = "Japan";
// 		return 0;
//     });
