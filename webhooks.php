<?php
//You shoud add var for your server request , api key, text, warning and timeout for pop up
$server = "http://127.0.0.1:8096";
$Text='Hello%20World';
$Header='warning';
$TimeoutMs='30000';
$api_key='XXXXXXXXXXXX';

//recieve webhooks get session_id
$raw_payload = file_get_contents('php://input');
$data = json_decode($raw_payload, true);
$file = '/var/www/html/debug.txt';
$device = $data["DeviceId"];

//get session_id
if  ($data["NotificationType"] == "PlaybackStart") {
$url_sess_id = "$server/Sessions?api_key=$api_key&deviceId=$device";

$che = curl_init();
curl_setopt($che, CURLOPT_URL, $url_sess_id);
curl_setopt($che, CURLOPT_RETURNTRANSFER, 1);
$output = json_decode(curl_exec($che), true);
$sess_id = $output['0']["Id"];
curl_close($che);

//send message
$url = "$server/Sessions/$sess_id/Message?api_key=$api_key";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/json",
);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = <<<DATA
{
  "Text": "$Text",
  "Header": "$Header",
  "TimeoutMs": "$TimeoutMs"
}
DATA;

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($curl);

}
?>
