<?php

namespace App\Http\Controllers;

use TwitterOAuth\Auth\SingleUserAuth as TwitterOAuth;
use TwitterOAuth\Serializer\ArraySerializer as TwitterSerializer;
use Illuminate\Http\Request;

class TwitterController extends Controller
{
    private $credentials = [
        'consumer_key' => 'dLDm9kp0I9raYSUhFraWugjA6',
        'consumer_secret' => 'UwaVvzZj1BoXNMmBEtebmDZwOfrBeNSUteA2bELHoM3oePl9to',
        'oauth_token' => '737760914825482240-gNw7HOp1tZJZniy83rtf8atzWJl8Ywn',
        'oauth_token_secret' => 'NvsvSzepJg0XuAocSa7O5tUamBXSufRqNg8wE73VNvjpx'
    ];

    public function index(Request $request)
    {
        //$term = "Secretaria Civil";
        $term = $request->input("term") ? : "Secretaria Civil";
        $tweet = $this->searchTwitter($term);
        return ($tweet) ? $tweet:"";

    }

    public function api()
    {

    }

    public function searchTwitter($term)
    {
        try {
            $tweet = null;
            $auth = new TwitterOAuth($this->credentials, new TwitterSerializer());
            $params = ['q' => $term,
                'count' => 1,
                'tweet_mode'=>'extended',
                'locale' => 'pt'
            ];
            if($auth) {
                $response = $auth->get('search/tweets', $params);
                if($response && count($response["statuses"]) > 0) {
                    $tweet = ["text"=>$response["statuses"][0]["full_text"],"user"=>$response["statuses"][0]["user"]["name"]];
                }
            }
            return $tweet;
        } catch(Exception $e) {
            return null;
        }  
    }
}
