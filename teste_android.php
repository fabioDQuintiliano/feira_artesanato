<?php


// API access key from Google API's Console




class Push{
    //var $API_KEY = "";
    public function send($msg,$registrationIds){
        $fields = array
        (
            'registration_ids'  => $registrationIds,
            'data'              => $msg
        );

        $headers = array
        (
            'Authorization: key=' . (getenv('FCM_SERVER_KEY') ?: ''),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        return $result;

    }
}
$registrationIds = array("cig9jepAoQs:APA91bGjJJt8Huvq5MbPA5JrBdC2X9IOgkLw4HiC0NuiarwTlep0Fpx8pILeH9X8CQfhSO6EwJT-TD9g1bcSVbChHb0Zfv91MBbMybtQD0AccQ_n09eetOhcxmR1Qmat8n333Iu0widu" );

// prep the bundle
$msg = array
(
    'message'       => 'here is a message. message',
    'title'         => 'This is a title. title',
    'subtitle'      => 'This is a subtitle. subtitle',
    'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
    'vibrate'   => 1,
    'sound'     => 1,
    'extra'     => 'fabio',
    "style"     => "inbox",
    "summaryText"=> "Você tem %n% novas notificações",
    
);


$ret = Push::send($msg,$registrationIds);
echo "<pre>";
print_r(json_decode($ret));
?>