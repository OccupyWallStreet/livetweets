<html>
    <head>
        <link rel="stylesheet" href="/style.css" type="text/css" media="screen" title="no title" charset="utf-8" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="header">
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
            
        </div>
        <div>
            Tweets refresh every 5 minutes
            <hr>
        </div>
        <!--next / prev-->
        <div id="tweets">
            <?php if (isset($tweets) && is_array($tweets) && (count($tweets) >0)):?>
            <?php foreach ($tweets as $tweet):?>
            <div class="tweet">
                <?php //only every 15 mins?>
                <div class="date">
                    <?=date("H:i a",strtotime($tweet["time"]))?><br/>
                </div>
                <div class="text">
                    <?=$tweet["text"]?><br/>
                </div>
            </div>
            
            <?php //only every 15 mins?>
            <div class="divider">
            </div>
            
            <?php endforeach;?>
            <?php else: ?>
            <?php endif;?>
        </div>
        
       <script type="text/javascript">
       $(document).ready(function(){
           $("#date-chooser").change(function(){
               var url = "/display/"+$("#date-chooser").val();
               window.location.href = url;
           })
       })
       </script> 
        <!--next / prev-->
    </body>
</html>