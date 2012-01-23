<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'?>
<rss version="2.0">
<channel>
<title>NYCGA Livetweets</title>
<description>Compilation of livetweets from NYC General Assembly and Spokes Council,  from the @libertysqga family of Twitter accounts.</description>
<link>http://livetweets.occupy.net/</link>
<lastBuildDate><?=date('D, d M Y H:i:s T',strtotime(time()))?></lastBuildDate>
<pubDate><?=date('D, d M Y H:i:s T',strtotime(time()))?></pubDate>
<?php if (isset($tweets) && is_array($tweets) && (count($tweets) >0)):?>
<?php foreach ($tweets as $by_date=>$tweet):?>
  <item>
  <title>Tweets for <?=date("D, d M Y", strtotime($tweet["date"]))?></title>
  <description><![CDATA[
    <?php foreach ($tweet["content"] as $id => $content): ?>
      <p><?=$content["text"]?></p>
    <?php endforeach ?>
    ]]>
  </description>
  <link>http://livetweets.occupy.net/display/<?=$tweet["date"]?></link>
  <guid isPermaLink="false"><?=$id?></guid>
  <pubDate><?=date('D, d M Y H:i:s T',strtotime($tweet["date"]))?></pubDate>
  </item>
<?php endforeach;?>
<?php else: ?>
<?php endif;?>
</channel>
</rss>