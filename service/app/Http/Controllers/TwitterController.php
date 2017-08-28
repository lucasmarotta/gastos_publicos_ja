<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;

class TwitterController extends Controller
{
    private $app = [
        'key' => 'dLDm9kp0I9raYSUhFraWugjA6',
        'secret' => 'UwaVvzZj1BoXNMmBEtebmDZwOfrBeNSUteA2bELHoM3oePl9to',
        'oauth_token' => '737760914825482240-gNw7HOp1tZJZniy83rtf8atzWJl8Ywn',
        'oauth_token_secret' => 'NvsvSzepJg0XuAocSa7O5tUamBXSufRqNg8wE73VNvjpx'
    ];

    public function index(Request $request)
    {
        $term = $request->input("term") ? : "Espírito Santo";
        $tweet = $this->searchTwitter($term);
        return ($tweet) ? $tweet:"{}";
    }

    private function searchTwitter($term)
    {
        try {
            $tweet = null;
            $conn = new TwitterOAuth($this->app["key"], $this->app["secret"], $this->app["oauth_token"], $this->app["oauth_token_secret"]);
            $params = ['q' => $term,
                'count' => 1,
                'tweet_mode'=>'extended',
                'result_type'=>'recent',
                'locale' => 'pt'
            ];
            if($conn) {
                $response = $conn->get('search/tweets', $params);
                if($response && count($response->statuses) > 0) {
                    $tweet = ["text"=>$response->statuses[0]->full_text,"user"=>"@".$response->statuses[0]->user->name];
                }
            }
            return $tweet;
        } catch(Exception $e) {
            return null;
        }  
    }
}
