<?php
namespace Firebase;
use \DAO, 
    \Kreait\Firebase\Factory,
    \Kreait\Firebase\Messaging\CloudMessage,
    \Kreait\Firebase\Messaging\Notification,
    \Kreait\Firebase\Messaging\AndroidConfig;

class NotificationFirebase{

/*
https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages/send

*/

	public $api_key ="chave aqui";
	public $project_id = "trico-be5b3";


	public $firebase_url = "https://fcm.googleapis.com/v1/";
	public $url;
	public $factory;
    public $messaging;
	public $database;
	public function __construct() {
		//$this->url = $this->firebase_url.'projects/'.$this->project_id.'/messages:send';

		$this->factory   = (new Factory)->withServiceAccount('classes/Firebase/firebase_credentials.json');
		$this->messaging = $this->factory->createMessaging();
        $this->database  = $this->factory->createDatabase();
  	}

    public function subscribe_topic($topic,$deviceToken){
        $this->messaging->subscribeToTopic($topic, [$deviceToken]);
    }



	public function sendToDevice($device_id,$title,$body,$imageUrl='',$data=false){


		$notification = Notification::fromArray([
		    'title' => $title,
		    'body' => $body,
		    'image' => $imageUrl,
		]);
        $config = AndroidConfig::fromArray([
            'ttl' => '3600s',
            'priority' => 'high',
            'notification' => [
                'title' =>  $title,
                'body' => $body,
                'icon' => 'ic_stat_name',
                'color' => '#f4c8af',
                "sound"=> "default" 
            ],
        ]);

        
        $topic = 'device-'.$device_id;
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification) // optional
            //->withData($data) // optional
        ;
        $message = $message->withAndroidConfig($config);

        $this->setNotificationDatabase($device_id,$title,$body,$imageUrl,$data);
        return $this->messaging->send($message);

	}
    function setNotificationDatabase($device_id,$title,$body,$imageUrl='',$data=false){


        $path = isDebug()?'developer':'production';
        $path .= '/notificacoes/'.$device_id.'/naolido';
        $db = $this->database->getReference($path);
      //  $dbKey = $db->getKey();
        $db->push([
            'title' => $title,
            'body' => $body,
            'image' => $imageUrl,
            'data' => $data,
            'lido' => 0,
            'time' => \Kreait\Firebase\Database::SERVER_TIMESTAMP
        ]);


        return;



    }
	

}

?>