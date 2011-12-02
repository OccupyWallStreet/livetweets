<?php

class Tweets {
    //run search
    //tweets to json
    //write to the cache file
    
    //read
    //load the cache file
    //wait
    //load the cache file
    var $accounts = array();
    
    function __construct(){
        $this->accounts = array("libertysqga","libertysqga2","libertysqga3","libertysqga4");
        $this->route();
    }
    
    function route(){
        if(isset($_GET["p"])){
            $func = $_GET["p"];
        } else {
            $func = "display";
        }
    
        $this->$func();
    }
    
    
    function get(){
        //https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=libertysqga&count=20
        $all_tweets = array();
        foreach ($this->accounts as $account){
             $tweets = file_get_contents("https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=".$account."&count=1000");         
            $tweets = json_decode($tweets,true);
            foreach ($tweets as $t) {
                $all_tweets[$t["id_str"]] = array("time"=>date("Y-m-d H:i:s" ,strtotime($t["created_at"])),"date"=>date("Y-m-d",strtotime($t["created_at"])),"text"=>$t["text"]);
            }
        } 
        ksort($all_tweets);
        $this->write($all_tweets);
        
    }
    
    function write($tweets){
        //tweets come in as an array;
        //split the array by day;
        $tweets_by_day = array();
        foreach ($tweets as $id=>$tweet) {
            $tweets_by_day[$tweet["date"]][$id] = $tweet;
        }
        foreach ($tweets_by_day as $date=>$tweets) {
            //file name is just today's date, whatever that may be
            $filename = "archive/".$date.".json";
            //get existing tweets and combine

            if (file_exists($filename)){
                $existing_tweets = file_get_contents($filename); 
                $existing_tweets = json_decode($existing_tweets,true);
                if (is_array($existing_tweets) && (count($existing_tweets)>1)) {
                    
                    $tweets = $existing_tweets + $tweets;
                    ksort($tweets);
                }
            }
            
            //tweets to text
            $tweets = json_encode($tweets);
            //rewrite the file, if it's today, otherwise skip
            file_put_contents($filename,$tweets);
        }
    }
    
    function check_latest() {
        //read in last-checked and parse as int
        $last_checked=file_get_contents("last_checked")+0;
        //older than 5 minutes?  check again
        if ((time() - $last_checked) > 300){
            $last_checked = time();
            file_put_contents("last_checked",$last_checked);
            $this->get();
        }
        
    }
    function display($date=null){
        $this->check_latest();
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
        }
        
        
        if ($date==null) {
            $date = date("Y-m-d",time());
        }
        $tweets = file_get_contents("archive/".$date.".json");
        $tweets = json_decode($tweets,true);
        $archives = scandir("archive/");
        unset($archives[0]); unset($archives[1]);
        $new = array();
        foreach ($archives as $a) {
            $new[] = array("filename"=>$a,"date"=>str_replace(".json","",$a));
        }
        $archives = array_reverse($new);
        include ("templates/display.php");
    }
}

$t = new Tweets();
exit();
