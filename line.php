<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'YeT2yrLsI0XVUldwTRNbD5dvKxMf1zjFmV4YXFQhFKJoYVzNh2NyGfs4d9VXIRzwdH0hmjRzqHd4dgjwbXnzJDlvPaWa1qNPHv0UGagxL66bXaKVDRoanhn7zE5qrT8HsDdmsUYBw/BddZi3ArJ3AgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "อุณหภูมิตอนนี้")||($text == "อุณหภูมิวันนี้")||($text == "อุณหภูมิ")){
			$temp = 27;
			$reply_message = 'ขณะนี้อุณหภูมิที่ '.$temp.'°C องศาเซลเซียส';
		}
		else if($text== "ข้อมูลส่วนตัวของผู้พัฒนาระบบ"){
			$reply_message = 'ชื่อนายกานต์ เจริญจิตร อายุ 22ปี น้ำหนัก 52kg. สูง 180cm. ขนาดรองเท้าเบอร์ 6 ใช้หน่วย US';
		}
	   	else if($text== "อยากทราบยอด COVID-19 ครับ"){
			$reply_message = '"รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 (COVID-19) ในประเทศไทย"
ผู้ป่วยสะสม    จำนวน 398,995 ราย
ผู้เสียชีวิต     จำนวน 17,365 ราย
รักษาหาย     จำนวน 103,753 ราย
ผู้รายงานข้อมูล: นายฐิติพงศ์ อ่อนจันทร์ 59160648
';
		}
		else
		{
			$reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
