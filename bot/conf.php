<?php
  
define('API_KEY','YourTelegramBotToken');
define('MAIN_DIR',$_SERVER['DOCUMENT_ROOT'].'/your_app_directory/');
define('MAIN_PATH','https://your.domain//your_app_directory//');
function robot($method,$datas=[]){
  $url = "https://api.telegram.org/bot".API_KEY."/".$method;
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
  $res = curl_exec($ch);
  if(curl_error($ch)){
      var_dump(curl_error($ch));
  }else{
      return json_decode($res);
  }
}

$database = json_decode( file_get_contents('data.json'));

function get_file_path($file_id){
    $url = 'https://api.telegram.org/bot'.API_KEY.'/getFile?file_id=' .$file_id;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $res = curl_exec($ch);
      var_dump($data = json_decode($res, true));
      return(  $data['result']['file_path']);

} 

function downloadFile($file, $path) 
{ 
    $err_msg = ''; 

    $out = fopen($path, 'wb'); 
    if ($out == FALSE){ 
    return "File not opened<br>"; 
    exit; 
    } 

    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_FILE, $out); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_URL, $file); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_exec($ch); 
    if(curl_error ($ch)){
        return curl_error ($ch);
    }

    curl_close($ch); 
    fclose($out); 
}

function wpupload($link){
    // //upload method 3
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.yourwpwebsite.ir/wp-json/wp/v2/media',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_USERPWD =>'your jwt',
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'file'=> new CURLFILE("$link"),
            'caption'=>'appduny'
        ),
        CURLOPT_HTTPHEADER => array(
            "Content-Disposition: attachment; filename=$link"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}

function wppost($postRequest){
    //post file in blog
   

    $cURLConnection = curl_init('https://www.yourWpWebsite.ir/wp-json/wp/v2/blog');
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($cURLConnection, CURLOPT_USERPWD, 'aref:your jwt');
    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);

    // $apiResponse - available data from the API request
    $jsonArrayResponse = json_decode($apiResponse);
    return ($jsonArrayResponse );
    // //------------------------
}

function get_madiapost_saveit($fileid,$chat_id,$caption,$database,$message_media_group_id){
    $filepath = get_file_path($fileid);
    $file_j_name = explode('/',$filepath);
    sendMessage($chat_id,downloadFile("https://api.telegram.org/file/bot".API_KEY."/$filepath",$file_j_name[1]));
    $wpupload_response = wpupload($file_j_name[1]);
    $source_url = $wpupload_response->source_url;
    $imgid = $wpupload_response->id;
    sendMessage($chat_id,'تصویر با موفقیت آپلود شد. لطفا عنوان پست را ارسال کنید');
    $database->aref->text=$caption;
    $database->aref->img_id=array($imgid);
    $database->aref->filenames=[$source_url]; 
    $database->aref->step = 'title';
    $database->aref->mediagroupid = $message_media_group_id;
    file_put_contents('data.json',json_encode($database)); 
}

function get_group_madiapost_saveit($fileid,$chat_id,$caption,$database,$message_media_group_id){
    $filepath = get_file_path($fileid); 
    $file_j_name = explode('/',$filepath); 
    sendMessage($chat_id,downloadFile("https://api.telegram.org/file/bot".API_KEY."/$filepath",$file_j_name[1]));
    $wpupload_response = wpupload($file_j_name[1]);
    $source_url = $wpupload_response->source_url; 
    $imgid = $wpupload_response->id;
    //$database->aref->text=$caption;
    $database->aref->img_id=array($imgid);
    array_push($database->aref->filenames,$source_url);
    $database->aref->step = 'title';
    $database->aref->mediagroupid = $message_media_group_id;
    file_put_contents('data.json',json_encode($database)); 
}
function deletefiles(){
    $dir = ''; // set directory path here
    
    $extensions = ['mp3', 'jpg', 'png', 'mp4', 'ogg']; // file extensions to delete
    
    foreach (glob('*') as $file) {
      if (in_array(pathinfo($file, PATHINFO_EXTENSION), $extensions)) {
        unlink($file);
       
      }
    }
}
