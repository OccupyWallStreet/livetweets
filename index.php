<pre>
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
        
    }
    
    function get(){
        //https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=libertysqga&count=20
        $all_tweets = array();
        foreach ($this->accounts as $account){
             $tweets = file_get_contents("https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=".$account."&count=100");         
            $tweets = json_decode($tweets,true);
            echo count($tweets);
            foreach ($tweets as $t) {
                $all_tweets[$t["id_str"]] = array("time"=>date("Y-m-d H-is" ,strtotime($t["created_at"])),"date"=>date("Y-m-d",strtotime($t["created_at"])),"text"=>$t["text"]);
            }
        } 
        ksort($all_tweets);
        $this->write($all_tweets);
        
    }
    
    function write($tweets){
        $filename = date("Y-m-d",time()).".json";
        //tweets come in as an array;
        //split the array by day;
        $tweets_by_day = array();
        foreach ($tweets as $tweet) {
            $tweets_by_day[$tweet["date"]][] = $tweet;
        }
        
        foreach ($tweets_by_day as $date=>$tweets) {
            //file name is just today's date, whatever that may be
            $filename = $date.".json";
            //tweets to text
            $tweets = json_encode($tweets);
            //rewrite the file
            file_put_contents($filename,$tweets);
        }
    }
    
    function dispay(){
        
    }
}

$t = new Tweets();
$t->get();
exit();
function eviction_tweets($page) {
      $tweets = ORM::factory('message')->find_all();
      $i=$page;
      
      $hashtags = array("%23occupyla","%23lapd","%23oo");
      foreach ($hashtags as $hashtag) { 
        $finished=1;
        while ($i < 100 && ($finished==1)){
          //get max_id
          //look for the hashtags in question = zuccotti, ows, ofs

          $max_id = $t->find(array(),array("id_str"=>true))->sort(array("id_str"=>1))->limit(1);
          $max_id = $max_id->getNext();
          $max_id = $max_id["id_str"];
          $max_id_string = ($max_id==null) ? "" :"+max_id%3A".$max_id;
              while ($page < 16 &&($finished==1)) {
                    $tweets = file_get_contents( "https://search.twitter.com/search.json?q=".$hashtag."+until%3A2011-11-29+since%3A2011-11-27".$max_id_string."&result_type=recent&include_entities=true&rpp=100&page=".$page);
                //query string for the whole query, so no page or max id
                $query_string = "https://search.twitter.com/search.json?q=%23".$hashtag."+until%3A2011-11-29+since%3A2011-11-27&result_type=recent&include_entities=true";
                $tweets = json_decode($tweets,true);
                foreach ($tweets["results"] as $tweet) {

                  //add some metadata from the crawler
                  $tweet["ows_meta"] = array("query_string"=>$query_string,"crawl_timestamp"=>time());

                  $t->update(array("id_str"=>$tweet["id_str"]), $tweet ,array("upsert"=>true));
                }
                $page = $page +1;
                if (count($tweets["results"])>0) {
                  sleep(15);
                  $finished=1;
                } else {
                  $finished = 0;
                }
              }
          $page = 1;
          $i=$i+1;
        }
      
      //upsert these to the main tweets table
      $alltweets = $t->find();
      $maintweets = $ows->selectCollection("tweets");
      while ($tweet = $alltweets->getNext()) {
        $maintweets->update(array("id_str"=>$tweet["id_str"]), $tweet ,array("upsert"=>true));
      }
      //clear the staging table
      $t->remove(array());
      }
      exit();
      
}