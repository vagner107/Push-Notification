<?php
 //generic php function to send GCM push notification
   function sendPushNotificationToGCM($registatoin_ids, $message, $title) {
  //Google cloud messaging GCM-API url
        $url = 'https://gcm-http.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
            "data" => array(
				"title" => $title,
				"message" => $message,
				'msgcnt' => count($message),
				'timestamp' => date('Y-m-d h:i:s'),
			  ),
        );
		
		
		// Google Cloud Messaging GCM API Key
		define("GOOGLE_API_KEY", "AIzaSyCf-3XogU2uG__8V06vt5R5QPSiLLQ6yNU");

		$headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);    
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
?>
<?php
 
 //this block is to post message to GCM on-click
$pushStatus = ""; 
 if(!empty($_GET["push"])) { 
  $gcmRegIDs  = file("GCMRegId.txt");
  for($i=0;$i<count($gcmRegIDs);$i++) {
	 $gcmRegIDs[$i] = trim($gcmRegIDs[$i]);
  }
  $pushMessage = $_POST["message"]; 
  $pushTitle = $_POST["title"]; 
  if (isset($gcmRegIDs)) {
   //$pushStatus = sendPushNotificationToGCM($gcmRegIds, $message);
   $pushStatus = sendPushNotificationToGCM($gcmRegIDs, $pushMessage, $pushTitle);
  }
 }
 
 //this block is to receive the GCM regId from external (mobile apps)
 if(!empty($_GET["shareRegId"])) {
  $gcmRegID  = $_POST["shareRegId"]; 
  file_put_contents("GCMRegId.txt",$gcmRegID);
  echo "Ok!";
  exit;
 } 
?>
<html>
    <head>
        <title>Google Cloud Messaging (GCM)</title>
    </head>
 <body>
  <h1>Google Cloud Messaging (GCM)</h1> 
  <form method="post" action="./?push=1">
   <div>
    <input type="text" name="title" placeholder="Titulo"/>
   </div>
   <div>
    <textarea rows="2" name="message" cols="23" placeholder="Mensagem"></textarea>
   </div>
   <div><input type="submit"  value="Enviar Push Notification via GCM" /></div>
  </form>
  <p><pre><?php var_dump(json_decode($pushStatus)); ?></pre></p>        
    </body>
</html>