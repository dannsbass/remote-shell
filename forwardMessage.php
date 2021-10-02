<?php
/**
 * script sederhana untuk mengambil id user yang pernah chat privat dengan bot asisten_robot
 * metode pengambilan adalah melalui arsip chat yang tersimpan di channel json
 * chat tersebut diforward untuk mendapatkan teksnya, lalu teksnya diurai untuk diambil id-nya saja
 * 
 * */
$max = 7573;
for ($i = 1; $i < $max; $i++) {
  $token = '1331845547:AAFOi_TPob2B2BU6BEYTCQV_ED8QnCB1T_c';#asisten_robot
  $chat_id =  -1001470252361;#channel testing
  $channel_id = -1001273006836;#channel json
  
  $url = "https://api.telegram.org/bot$token/forwardMessage";
  
  $ch = curl_init();
  $data = [
    'chat_id' => $chat_id,
    'from_chat_id' => $channel_id,
    'message_id' => $i
  ];
  ##############################
  curl_setopt_array($ch,[
    CURLOPT_URL=>$url,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_POSTFIELDS=>$data
  ]); 
  $respon = curl_exec($ch);
  curl_close($ch);
  ##############################
  $urai = json_decode($respon);
  if(isset($urai->ok) and false === $urai->ok){
    echo $urai->ok."$i false\n";
    continue;
  }
  if(isset($urai->result->text)){
    $text = $urai->result->text;
    $text = str_replace('_"message"','"message"',$text);
    $json = json_decode($text);
    if($json == null){
      echo "$i null\n";
      echo $text."\n";
      continue;
    }
    if(isset($json->message->from->id) and isset($json->message->chat->type) and 'private' == $json->message->chat->type){
      $id = $json->message->from->id;
      file_put_contents('id.txt',$id."\n",FILE_APPEND);
      echo "$i id-3 masuk: $id\n";
    }else{
      echo "$i tidak masuk\n";
    }
  }
  sleep(1);
}
