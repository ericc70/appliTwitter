<?php

namespace App\Service;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TwitterApiService
{
    private $getParams;

    public function __construct(ParameterBagInterface $getParams, HttpClientInterface $client)
    {
        $this->getParams = $getParams;
        $this->client = $client;
    }

    protected function auth()
    {
        $consumerKey = $this->getParams->get('TWITTER_CONSUMER_KEY');
        $consumerSecret = $this->getParams->get('TWITTER_CONSUMER_SECRET');
        $accesToken = $this->getParams->get('TWITTER_ACCESS_TOKEN');
        $accesTokenSecret = $this->getParams->get('TWITTER_ACCESS_TOKEN_SECRET');
        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accesToken, $accesTokenSecret);
        return $connection;
    }

    public function verifConnection()
    {
        dd($this->auth()->get("account/verify_credentials"));
    }
    
    public function post(string $content)
    {
         $this->auth()->post("statuses/update", ["status" => $content]);

        //  if ($this->auth()->getLastHttpCode() == 200) {
        //     // Tweet posted successfully
        // } else {
        //     // Handle error case
        // }
    }

    public function getUserTweet() :array{
        return $this->auth()->get("statuses/user_timeline");
    }
    public function getTimelimeTweet() :array{
        return $this->auth()->get("statuses/home_timeline");
    }
    
    public function getTweet(int $id) :object {
        return $this->auth()->get("statuses/show", ['id' =>$id, 'tweet_mode'=> 'extended'] );
    }

    public function getRetweets(int $id) :array {
        return $this->auth()->get("statuses/retweets", ['id' =>$id] );
    }
/*
    public function uploadImage(string $content) :array {
        dd ($this->auth()->get("media/upload", ['media' =>$content]) );
    }
*/
    /* Mention*/
    public function getMention() :array {
     return   $this->auth()->get("statuses/mentions_timeline" );
    }



    /* direct message */
    public function getDirectMessage():object{
   return   $this->auth()->get("direct_messages/events/list");
   
    }


    // public function getDirectMessage():array {
    //     $id = 1354817184015421454;
    //     dd(  $this->auth()->get('direct_messages/sent'));
      
    //    }

    public function postDirectMessage(string $content, int $id) 
    {

        $params = [
            'event' => [
                'type' => "message_create",
              
                'message_create' => [
                  
                    'target' => [
                        'recipient_id' => $id
                    ],
                    'message_data' => [
                        'text' => $content,

                    
                    ]
                ]
                
            ]
        ];
        
       dd(  $this->auth()->post("direct_messages/events/new", $params, true ) );
   
    }

    public function deleteDirectMessage(int $id) :void
    {

        dd( $this->auth()->delete("direct_messages/events/destroy", ['id '=> $id]) );

    }

    /* direct_messages/welcome_messages */
    public function newDirectMessageW( )
    {

        $params = [
            'welcome_message' => [
                'name' => "first welcome message",
                'message_data' => [
                    'text' => "Welcome !",
              
                ]
            ]
        ];
      

       dd(  $this->auth()->post("direct_messages/welcome_messages/new", $params, true ) );
   
    }

/*
    public function postDirectMessage(string $content, int $id)
    {

        $params = [
            'event' => [
                'type' => "message_create",
              
                'message_create' => [
                  
                    'target' => [
                        'recipient_id' => $id
                    ],
                    'message_data' => [
                        'text' => $content,

                        'quick_reply' => [
                            'type' => "options",
                            'options' => [

                                ["label" => "Red Bird"],
                                ["label" => "Red Bird"],
                                ["label" => "Red Bird"],
                             

                            ],
                        ],
                    ]
                ]
                
            ]
        ];
        
       dd(  $this->auth()->post("direct_messages/events/new", $params, true ) );
   
    }*/
}
