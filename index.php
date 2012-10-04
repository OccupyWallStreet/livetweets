<?php

class Tweets {
    var $accounts = array();
    var $params = array();
    var $date = null;
    function __construct(){
        //the twitter accounts to pull 
        $this->accounts = array("OWSPA","libertysqga","libertysqga2","libertysqga3","libertysqga4");
        $this->route();
    }
    
    function route(){
        //parse url
	
        $url = explode("/",$_SERVER["REQUEST_URI"]);
        //"display" is the default function
        if(isset($url[1]) && strlen($url[1])>0){
            $func = $url[1];
        } else {
            $func = "display";
        }
        
        //pass any additional url parts as params
        if (isset($url[2])){
            $param = $url[2];
        } else {
            $param = null;
        }
        unset($url[0]);unset($url[1]);
        $this->params = $url; 
        //execute the function
        $this->$func($param);
    }
    
    
    private function get(){
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
    
    private function write($tweets){
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
            //previous archives don't get touched
           // if (strpos($filename, date("Y-m-d",time())) !== false){
                file_put_contents($filename,$tweets); 
            //}
        }
    }
    //function to rebuild all archives
    
    
    //check latest
    private function check_latest() {
        //only if we're looking at today's tweets.
        if (($this->date==null ) || ($this->date==date("Y-m-d",time()))){
            //read in last-checked and parse as int
            $last_checked=file_get_contents("last_checked")+0;
            //older than 5 minutes?  check again
            if ((time() - $last_checked) > 300){
                $last_checked = time();
                file_put_contents("last_checked",$last_checked);
                $this->get();
            }
        } 
    }
    
    function build($pass) {
        
        //re-crawl all of the tweets
        if ($pass !="tw33tzd33tz") {
            echo "...";
            exit();
        }
        //https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=libertysqga&count=20
        $all_tweets = array();
        foreach ($this->accounts as $account){
            $page = 0;
            while ($page < 16){
                 $tweets = file_get_contents("https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=".$account."&count=200&include_rts=1&page=".$page);         
                $tweets = json_decode($tweets,true);
                foreach ($tweets as $t) {
                    $all_tweets[$t["id_str"]] = array("time"=>date("Y-m-d H:i:s" ,strtotime($t["created_at"])),"date"=>date("Y-m-d",strtotime($t["created_at"])),"text"=>$t["text"]);
                
                }
                $page++;
            }
        } 
        ksort($all_tweets);
        $this->write($all_tweets);
        //get all the tweets
        
    }

    function rss() {
      
      $archives = array_reverse(scandir("archive/"));
      unset($archives[0]);
      $archives = array_slice($archives,0,50);
      $tweets = array();
      foreach ($archives as $archive) {
        if (file_exists("archive/".$archive)) {
          $tweet = file_get_contents("archive/".$archive);
          $tweets[$archive]["date"] = str_replace(".json","",$archive);
          $tweets[$archive]["content"] = json_decode($tweet,true);
        }
      }
      header("Content-type: text/xml");
      include ("templates/rss.php");
    }

    function display($date=null){
        $this->date = $date;
        $this->check_latest();
       
        if ($date==null) {
            $date = date("Y-m-d",time());
        }
        if (file_exists("archive/".$date.".json")) {
            $tweets = file_get_contents("archive/".$date.".json");
            $tweets = json_decode($tweets,true);
        }

        $archives = scandir("archive/");
        unset($archives[0]); unset($archives[1]);
        $new = array();
        foreach ($archives as $a) {
            $new[] = array("filename"=>$a,"date"=>str_replace(".json","",$a));
        }

        $archives = array_reverse($new);
        if ($archives[0]["filename"]=="empty") {
            unset($archives[0]);
        }
        include ("templates/display.php");
    }
    //handles the @, #, http business (needs to recognize t.co)
    function format_tweet($text){
        //chunk the post for @ and #
        $text = explode(" ",$text);
        foreach ($text as &$t) {
            if (substr($t,0,1)=="@"||substr($t,0,2)==".@") {
                $x = str_replace(array("@",".",",","?","!",'"',"'",":"),"",$t);
                $t = '<a href="http://twitter.com/'.$x.'" target="_new" class="at">'.$t.'</a>';
            }


            if (substr($t,0,1)=="#") {
                $x = str_replace("#","",$t);
                $t = '<a href="http://twitter.com/#!/search?q=%23'.$x.'" target="_new" class="hash">'.$t.'</a>';
            }
             if (substr($t,0,7)=="http://") {
                    $t = '<a href="'.$t.'" target="_new" class="link">'.$t.'</a>';
            }
            
             if (substr($t,0,4)=="t.co") {
                    $t = '<a href="http://'.$t.'" target="_new" class="link">'.$t.'</a>';
            }
        }
        $text = implode(" ",$text);
        return $text;
    }
}

$t = new Tweets();
