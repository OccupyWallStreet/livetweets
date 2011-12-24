<html>
    <head>
        <title>Liberty Square GA Livetweets for <?=date("l, F j, Y",strtotime($date))?> | Occupy.net - Services for Occupy Wall Street</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta content="A Compilation of the livetweets for the Liberty Square General Assembly and NYC Spokes Council" name="description">
        <link rel="stylesheet" href="/style.css" type="text/css" media="screen" title="no title" charset="utf-8" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="header">
            <div id="occupy-net">
                <a href="http://occupy.net"><img src="/img/occupy.jpg" alt="occupy.net" /></a>
            </div>
            
            <h1>Tweets for <?=date("l, F j, Y",strtotime($date))?></h1>
       <!-- date picker-->
            <div id="menu">
                <form action="#">
                    <select id="date-chooser">
                        <?php if (isset($archives) && is_array($archives) && (count($archives) >0)):?>
                            <option value="">--select date--</option>
                        <?php foreach ($archives as $archive):?>
                            <option value="<?=$archive["date"]?>"><?=$archive["date"]?></option>
                        <?php endforeach;?>

                        <?php else: ?>
                        
                        <?php endif;?>
                        
                    </select>
                </form>
                
            </div>
        
            <div>
                <ul>
                    <li><a href="javascript:void(0)" class="terms-link">Terms</a> used by the GA and Spokes</li>
                    <li><a href="http://www.nycga.net/<?=date("Y/m/d/",strtotime($date))?>" target="_new">Proposals and minutes</a> from this day</li>
                </ul>
                <div id="terms">
                    <h3>Notes</h3>
                    
                    Stairs:    facilitators (ppl who facilitate the meeting), because they stand on the stairs at Zuccotti.<br/>
                    WG:    working group(s)<br/>
                    CQ:    clarifying question<br/>
                    C:    concern<br/>
                    FA:    friendly amendment<br/>
                    PoP:    point of process, used when someone is essentially speaking out of turn or not respecting the process.<br/>
                    PoI:    point of information, used when someone has factual information that is directly relevant to the issue at hand and will help people make a decision.<br/>
                    Block:    VERY serious, sometimes misused. Meant to block a proposal because the blocker has very serious moral, ethical, or safety issues directly related to the proposal and is willing to leave the movement if the proposal is consensed upon.<br/>
                    Stack:    Essentially a list of people who have questions or concerns. Stack is &ldquo;taken&rdquo;, questions/concerns are heard, and stack is &ldquo;closed&rdquo;.<br/>
                    <br/>
                    ppl:    people<br/>
                    SIS:    Shipping, Inventory, and Storage Working Group<br/>
                    GA:    General Assembly<br/>
                    SC:    Spokes Council<br/>

                    <h3>Nonverbal handsigns:</h3>
                    Up-twinkles:    We agree with what the speaker is saying; we’re with you!<br/>
                    Mid-twinkles:    Not so sure about that one.<br/>
                    Down-twinkles:    Not with the speaker on that.<br/>
                    Hand-triangle:    Point of Process<br/>
                    Finger pointed up: Point of Information<br/>
                    Arms crossed:    Block.<br/>
                    Rolling hands:    We love you, we understand your point, please wrap it up!<br/><br/>
                    (<i>terms taken courtesy of this <a href="https://docs.google.com/a/nycga.net/document/d/1haAOPMZATboTK9LpA22qkVOb-i5rpJTYq2bRdYCR4VU/edit">document</a></i>)

       <!--                 uptwinkles    midtwinkles    downtwinkles    CQ    PoI    PoP    block-->
        
            <div class="right">
                <a href="javascript:void(0)" class="terms-link">[hide]</a>
            </div>
            
                </div>
                
                
            </div>
            <!--next / prev-->
            
        </div>
        <div id="tweets">
            <?php if (isset($tweets) && is_array($tweets) && (count($tweets) >0)):?>
            <?php foreach ($tweets as $tweet):?>
            <div class="tweet">
                <?php //only every 15 mins?>
                <div class="date">
                    <?=date("H:i a",strtotime($tweet["time"]))?><br/>
                </div>
                <div class="text">
                    <?=$this->format_tweet($tweet["text"])?><br/>
                </div>
            </div>
            
           
            
            <?php endforeach;?>
            <?php else: ?>
                No tweets yet today.  Check the archives for past tweets.
            <?php endif;?>
            <div class="next-prev">
                <div class="prev button">
                    &larr; &nbsp;<a href="/display/<?=date("Y-m-d",strtotime ($date)-(24*3600))?>">Prev</a>
                </div>
                <div class="next button">
                    <a href="/display/<?=date("Y-m-d",strtotime($date)+(24*3600));?>">Next</a>&nbsp; &rarr;
                </div>
            </div>
        </div>
        <div id="footer">
            This is a compilation of the livetweets from the <a target="_new" href="http://twitter.com/LibertySqGA">@libertysqga</a> family of twitter accounts.  Thanks to all of our live-tweeters!<br/>Tweets refresh every 5 minutes.<br/>
            
        Brought to you by the
        <a target="_blank" href="http://www.nycga.net/groups/internet">NYC General Assembly Tech Ops group</a>
        </div>
        
       <script type="text/javascript">
       $(document).ready(function(){
           $("#date-chooser").change(function(){
               var url = "/display/"+$("#date-chooser").val();
               window.location.href = url;
           })
           $(".terms-link").click(function(){
              $("#terms").slideToggle(200,"swing") 
           })
       })
       </script> 
        <!-- Piwik --> 
        <script type="text/javascript">
        var pkBaseURL = (("https:" == document.location.protocol) ? "https://analytics.occupy.net/" : "http://analytics.occupy.net/");
        document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
        </script><script type="text/javascript">
        try {
        var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
        piwikTracker.trackPageView();
        piwikTracker.enableLinkTracking();
        } catch( err ) {}
        </script><noscript><p><img src="http://analytics.occupy.net/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
        <!-- End Piwik Tracking Code -->
    </body>
</html>