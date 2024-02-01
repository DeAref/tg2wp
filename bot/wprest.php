<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
//error_log( "Hello, errors!" );


// if(function_exists('litespeed_finish_request'))
//     litespeed_finish_request();
// elseif(function_exists('fastcgi_finish_request'))
//     fastcgi_finish_request();
    

//it is Telegram ??
$telegram_ip_ranges = [
    ['lower' => '149.154.160.0', 'upper' => '149.154.175.255'], 
    ['lower' => '91.108.4.0',    'upper' => '91.108.7.255'],    
];

$ip_dec = (float) sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
$ok=false;

foreach ($telegram_ip_ranges as $telegram_ip_range) if (!$ok) {
    $lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower']));
    $upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
    if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) $ok=true;
}
if (!$ok) die("Hmm, I don't trust you...");


date_default_timezone_set('Asia/Tehran');
include_once 'conf.php';
///==============================
$update = json_decode(file_get_contents('php://input'));

if(isset($update->message)){
  $message = $update->message;
  $message_date = $update->chat->date;
  $chat_id = $message->chat->id;
  $from_id = $message->from->id;
  $text = $message->text;
  $message_id = $message->message_id;
  $name = $message->from->first_name;
  $username = $message->from->username;
  $photo = $message->photo;
 
}
if(isset($update->callback_query->data)){
  $data = $update->callback_query->data;
  $chatid = $update->callback_query->message->chat->id;
  $fromid = $update->callback_query->from->id;
  $messageid = $update->callback_query->message->message_id;
  $name1 = $update->callback_query->from->first_name;
  $user_name = $update->callback_query->from->username;
  $gpname = $update->callback_query->message->chat->title;
  $callback_query_id = $update->callback_query->id;
}

if(isset($update->reply_to_message)){
  $rt = $update->message->reply_to_message;
  $reid = $update->message->reply_to_message->forward_from->id;
  $reuser = $update->message->reply_to_message->from->username;
  $rename = $update->message->reply_to_message->from->first_name;
  $remsgid = $update->message->reply_to_message->message_id;
}
if(isset($update->message->new_chat_member)){
  $newchatmemberid = $update->message->new_chat_member->id;
  $newchatmemberu = $update->message->new_chat_member->username;
}
if(isset($update->message->chat)){
  $tc = $update->message->chat->type;
  $namegroup = $update->message->chat->title;
}
if(isset($update->message->caption)){
  $caption = $update->message->caption;
}
if(isset($update->edit_message)){
  $chat_edit_id = $update->edited_message->chat->id;
  $message_edit_id = $update->edited_message->message_id;
  $edit_for_id = $update->edited_message->from->id;
}
if(isset($update->message->audio)){
  $file_id = $update->message->audio->file_id;
  $duration = $update->message->audio->duration;
  $type = $update->message->audio->mime_type;
 
}
if(isset($update->message->video)){
  $vfile_id = $update->message->video->file_id;
  $vduration = $update->message->video->duration;
  $vtype = $update->message->video->mime_type;
 
}
if(isset($update->message->voice)){
  $voicefile_id = $update->message->voice->file_id;
  $voiceduration = $update->message->voice->duration;
  $voicetype = $update->message->voice->mime_type;
  
}if(isset($update->message->animation)){
  $giffile_id = $update->message->animation->file_id; 
  $gifduration = $update->message->animation->duration;
  $giftype = $update->message->animation->mime_type;
  $gifwidth = $update->message->animation->width;
}
if(isset($update->message->photo)){
  $photoindex = end($update->message->photo);
  $photofile_id = $photoindex->file_id; 
  $photo_type = $photoindex->mime_type;
  $photo_width =$photoindex->width;
   
}


//simple anti Hack 
// if(strpos($text,"'") !== false or strpos($text,'"') !== false or strpos($text,",") !== false or strpos($text,'$') !== false or strpos($text,"}") !== false or strpos($text,";") !== false or strpos($text,"{") !== false){
//     robot('sendMessage',[
//       'chat_id'=>$adminChatId,
//       'text'=>"
//       یکی ازکاربران کاراکتر های غیرعادی به ربات ارسال کرده که ممکن قصد خراب کاری داشته باشه.
//       آیدی فرد: `$chat_id`
      
//       متن ارسالی: 
//       `$text`
//       ","parse_mode"=>'markdown',
//     ]);
//     //die;exit ();
// }




$day = date("d")+1;
$year = date("Y");
$month = date("n");
$nextdate = "$year-$month-$day";

$time = date("h:i");


/////////////
function sendMessage($chat_id,$text){
    robot('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>$text,
    ]);
}
function SendPhoto($chat_id,$photo,$caption){
    robot('SendPhoto',[
      'chat_id'=>$chat_id,
      'photo'=>$photo,
      'caption'=>$caption,
    ]);
}
function senddocument($chat_id,$document,$caption){
    robot('senddocument',[
      'chat_id'=>$chat_id,
      'document'=>$document,
      'caption'=>$caption,
    ]);
}
function sendInlinebutton($chat_id,$ButtonRow,$Text,$messageid){
  //buttonrow template:
  /*
    [['text' => "is this test button", 'callback_data' => "btnid-15"]];
  */
    robot('sendmessage', [
      "chat_id" => $chat_id,
      'message_id'=>$messageid,
      "text" => $Text,
      'reply_markup' => json_encode([
          "one_time_keyboard" => true,
          'inline_keyboard'=> $ButtonRow
      ])
    ]);
}
function editMessage($chatid,$messageid,$text){
  robot('editmessagetext', ["chat_id" => $chatid,'message_id'=>$messageid, "text" => "$text","parse_mode"=>'markdown']);
}
///============================================







if($update){
  if($chat_id == '1080648232'|| $chatid == '1080648232' || $chat_id == '1384920974'|| $chatid == '1384920974'){
    if(strpos($update->callback_query->data,"btnid-") !== false){
      $ex = explode("-",$update->callback_query->data);
      $btnid=$ex[1];
      $title = $database->aref->title;
      $textofpost = $database->aref->text;
      $img_tag = $database->aref->img_tag;
      $img_id = $database->aref->img_id;
      //publish
      if($btnid == '1'){
        $postRequest = array(
          "title" => $title,
          "content" => $img_tag."</br>".$textofpost,
          "status" => "publish",
          "featured_media" => $img_id[0]
        );
        $wppostResponse = wppost($postRequest);
        $database->aref->title='';
        $database->aref->text= '';
        $database->aref->img_tag='';   
        $database->aref->step='';
        $database->aref->filenames='';
        $postlink = $wppostResponse->link;
        deletefiles();
        editMessage($chatid,$messageid,"منتشر شد😃⭐️🎉
        link: $postlink
        ");
        sendMessage('1384920974',"    
        کاربر $name1 پست جدید ایجاد کرد:  
        $postlink 
        ");
        sendMessage('1080648232',"    
         کاربر $name1 پست جدید ایجاد کرد:  
        $postlink 
        ");
      }
      //draft
      elseif($btnid == '2'){
        $postRequest = array(
          "title" => $title,
          "content" => $img_tag."</br>".$textofpost,
          "status" => "draft",
          "featured_media" => $img_id[0]
        );
        $wppostResponse = wppost($postRequest);
        $postlink = $wppostResponse->link;
        $database->aref->title='';
        $database->aref->text= '';
        $database->aref->img_tag='';   
        $database->aref->step='';
        $database->aref->filenames='';
        $enjson = json_encode($wppostResponse);
        editMessage($chatid,$messageid," به صورت پیشنویس ذخیره شد.
        ");
        sendMessage('1384920974',"  پیشنویس ایجاد کرد $name1 کاربر");
        sendMessage('1080648232',"  پیشنویس ایجاد کرد $name1 کاربر");
        
      }
      //delete
      elseif($btnid == '3'){
        $database->aref->title='';
        $database->aref->text= '';
        $database->aref->img_tag='';   
        $database->aref->step='';
        $database->aref->filenames='';
        editMessage($chatid,$messageid,' حذف شد.');
      }
    }
   
  if(isset($vfile_id)){$fileid=$vfile_id;}elseif(isset($file_id)){$fileid=$file_id;}elseif(isset($voicefile_id)){$fileid=$voicefile_id;}elseif(isset($photofile_id)){$fileid=$photofile_id;}elseif(isset($giffile_id)){$fileid=$giffile_id;}
  if(isset($photo)||isset($vfile_id)||isset($file_id)||isset($voicefile_id)||isset($giffile_id)){
  
      $message_media_group_id = $message->media_group_id;
      $database_media_group_id = $database->aref->mediagroupid;
      //for debug   sendMessage($chat_id,$message_media_group_id.'e');   
      //if madia is gallery
      if(isset($message_media_group_id)){
        if($message_media_group_id == $database_media_group_id){   
          get_group_madiapost_saveit($fileid,$chat_id,$caption,$database,$message_media_group_id);  
        }else{
          get_madiapost_saveit($fileid,$chat_id,$caption,$database,$message_media_group_id); 
          get_group_madiapost_saveit($fileid,$chat_id,$caption,$database,$message_media_group_id);  
        }
      }else{
        get_madiapost_saveit($fileid,$chat_id,$caption,$database,$message_media_group_id); 
      }
      
      
  //-------------------
  }  
    if(isset($text)){
      if($database->aref->step == 'title'){
        $database->aref->title=$text;
        $database->aref->step = '';
        file_put_contents('data.json',json_encode($database));
        $photoslink = $database->aref->filenames;
        foreach($photoslink as $photolink){
          $img_tag = $img_tag."<img src='$photolink'/></br>"; 
        }
        //save img_tag for use in wppost
        $database->aref->img_tag = $img_tag;
        file_put_contents('data.json',json_encode($database));
        $title = $database->aref->title;
        $textofpost = $database->aref->text;
        $Text ="
        عنوان پست: $title
        
        متن: $textofpost
        "; 
        $ButtonRow = [[['text' => "انتشار 🟢", 'callback_data' => "btnid-1"]],[['text' => "ذخیره به عنوان پیشنویس 🟠", 'callback_data' => "btnid-2"]],[['text' => "صرف نظر کردن🔴", 'callback_data' => "btnid-3"]]];
        sendInlinebutton($chat_id,$ButtonRow,$Text,$messageid);
       
      }
      if($text == '/start'){
        sendMessage($chat_id,'hi ...');
      }
    }
    if(isset($update->message->audio)){
      //sendMessage($chat_id,'recive Audio');
    }
    if(isset($update->message->video)){
      //sendMessage($chat_id,'recive vid');
    }if(isset($update->message->animation)){
      //$filepath = get_file_path($giffile_id);
      //sendMessage($chat_id,$filepath); 
     
    }
  }
}



   

// $cURLConnection = curl_init();

// curl_setopt($cURLConnection, CURLOPT_URL, 'https://www.appduny.ir/wp-json/wp/v2/posts');
// curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

// $phoneList = curl_exec($cURLConnection);
// curl_close($cURLConnection);

// var_dump ( json_decode($phoneList));



// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, 'https://www.appduny.ir/wp-json/wp/v2/users?context=edit');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
// curl_setopt($ch, CURLOPT_USERPWD, 'aref:4G96 X7aT CoZD jRrT yDAU zNp5');

// $response = curl_exec($ch);
// var_dump($response);
// curl_close($ch);

//upload media:
// $filePath =  file_get_contents('Untitled-3.png');
// echo("<img src='Untitled-3.png'></img>");
// $url = 'https://www.appduny.ir/wp-json/wp/v2/media';
// $ch = curl_init();
// $username = 'aref';
// $password = '4G96 X7aT CoZD jRrT yDAU zNp5';
 
// curl_setopt( $ch, CURLOPT_URL, $url );
// curl_setopt( $ch, CURLOPT_POST, 1 );
// curl_setopt( $ch, CURLOPT_POSTFIELDS, $filedata );
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt( $ch, CURLOPT_HTTPHEADER, [
// 	'Content-Disposition: filename=examp00000le.png',
// 	'Authorization: Basic ' . base64_encode( $username . ':' . $password ),
// ] );
// $result = curl_exec( $ch );
// curl_close( $ch );
// print_r( json_decode( $result ) );

// $filePath =  'Untitled-3.png';
// $file_contents = file_get_contents($filePath);
// $file_name = basename($filePath);
// $file_type = mime_content_type($filePath);
// $postRequest = array(
//     'file' => $file_contents,
//     'filename' => $file_name,
//     'content-type' => $file_type,
//     'title' => 'My Uploaded Image',
//     'description' => 'This is an image uploaded via REST API.'
// );

// $cURLConnection = curl_init('https://www.appduny.ir/wp-json/wp/v2/media');
// curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
// curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($cURLConnection, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
// curl_setopt($cURLConnection, CURLOPT_USERPWD, 'aref:4G96 X7aT CoZD jRrT yDAU zNp5');
// // curl_setopt( $cURLConnection, CURLOPT_HTTPHEADER, [
// //     'Content-Disposition' => 'filename=name-of-file.png'
// //     ] );
// $apiResponse = curl_exec($cURLConnection);
// curl_close($cURLConnection);

// // $apiResponse - available data from the API request
// $jsonArrayResponse - json_decode($apiResponse);
// var_dump ($apiResponse );

