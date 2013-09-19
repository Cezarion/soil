<?php
/**
  *  Speed Up your connection to social website
  *  @author Mathias Gorenflot <mathias.gorenflot@inconito.fr>
  *
  */


/* *************************************************************************
  * Inspired by "My Twitter Widget"
  * Contributors: skywebdesign
  * Plugin link : http://wordpress.org/plugins/my-twitter-widget/
  * Donate link: http://www.dallasprowebdesigners.com
  *
  *  in your_theme_name/functions.php set TWITTER app values
  *  or in your_theme_name/lib.config.php
  * ************************************************************************* */

/*
  * @todo : use twitter api connect : https://github.com/abraham/twitteroauth
 */

class TwitterConnect
{

    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
    public  $number_of_tweets = 10;

    const TWITTER_API_URL = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    const TWITTER_OAUTH_SIGNATURE_METHOD = 'HMAC-SHA1';
    const TWITTER_OAUTH_VERSION = '1.0';


    function __construct( $nb_tweet = NULL  )
    {
        $this->consumer_key          = TWITTER_CONSUMER_KEY;
        $this->consumer_secret      = TWITTER_CONSUMER_SECRET;
        $this->access_token            = TWITTER_ACCESS_TOKEN;
        $this->access_token_secret = TWITTER_ACCESS_TOKEN_SECRET;
    }

    /* Create request
    /* ************************************************************************ */

    private function set_oauth_hash()
    {
        $time = time();
        $oauth_hash = array
        (
            'oauth_consumer_key' => $this->consumer_key,
            'oauth_nonce'  =>  $time,
            'oauth_signature_method' => self::TWITTER_OAUTH_SIGNATURE_METHOD,
            'oauth_timestamp'  =>  $time,
            'oauth_token' => $this->access_token,
            'oauth_version' => self::TWITTER_OAUTH_VERSION
        );
        return http_build_query($oauth_hash);
    }

    private function set_signature()
    {
        $base  = 'GET&'.rawurlencode(  $this->set_request_url() );
        $base .= '&'.rawurlencode( $this->set_oauth_hash() );

        $key  = rawurlencode( $this->consumer_secret);
        $key .= '&';
        $key .= rawurlencode( $this->access_token_secret);

        $signature = base64_encode(hash_hmac('sha1', $base, $key, true));

        return  rawurlencode($signature);
    }

    private function set_oauth_header()
    {
        $oauth_header  = 'oauth_consumer_key="'.$this->consumer_key.'", ';
        $oauth_header .= 'oauth_nonce="' . time() . '", ';
        $oauth_header .= 'oauth_signature="' . $this->set_signature() . '", ';
        $oauth_header .= 'oauth_signature_method="'.self::TWITTER_OAUTH_SIGNATURE_METHOD.'", ';
        $oauth_header .= 'oauth_timestamp="' . time() . '", ';
        $oauth_header .= 'oauth_token="'.$this->access_token.'", ';
        $oauth_header .= 'oauth_version="'.self::TWITTER_OAUTH_VERSION.'", ';

        return $oauth_header;
    }

    private function set_request_url ()
    {
       /*
            $data= array
            (
                'count' => $this->number_of_tweets
            );
        */
        return self::TWITTER_API_URL; //.'?'.http_build_query($data);
    }

    public function featch_tweets()
    {
        $curl_header = array("Authorization: Oauth {$this->set_oauth_header()}", 'Expect:');
        $curl_request = curl_init();
        curl_setopt( $curl_request, CURLOPT_HTTPHEADER, $curl_header   );
        curl_setopt( $curl_request, CURLOPT_HEADER, false  );
        curl_setopt( $curl_request, CURLOPT_URL, $this->set_request_url() );
        curl_setopt( $curl_request, CURLOPT_RETURNTRANSFER, true   );
        curl_setopt( $curl_request, CURLOPT_SSL_VERIFYPEER, false  );

        $json = curl_exec( $curl_request   );
        curl_close( $curl_request  );
        return json_decode($json);
    }

    /* Parse response
    /* ************************************************************************ */

    function make_links($tweet = '')
    {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if( preg_match( $reg_exUrl, $tweet, $url ) )
        $tweet = preg_replace( $reg_exUrl, "<a target=\"_blank\" href=".$url[0].">{$url[0]}</a> ", $tweet);
        return $tweet;
    }

    function make_mentions($tweet = '')
    {
        $regex = "/@[a-zA-Z0-9\_]*/";
        if( preg_match_all( $regex, $tweet, $matches ) ){
            foreach( (array) $matches[0] as $match ){
                $url = 'https://twitter.com/'.str_replace('@', '', $match);
                $tweet = str_replace( $match, "<a target=\"_blank\" href=".$url.">{$match}</a> ", $tweet);
            }
        }
        return $tweet;
    }

    function make_hashes($tweet = '')
    {
        $regex = "/#[a-zA-Z0-9\_\-]*/";
        if( preg_match_all( $regex, $tweet, $matches ) ){
            foreach( (array) $matches[0] as $match ){
                $url = 'https://twitter.com/search?q=%23'.str_replace('#', '', $match).'&src=hash';
                $tweet = str_replace( $match, "<a target=\"_blank\" href=".$url.">{$match}</a> ", $tweet);
            }
        }
        return $tweet;
    }

    /* Parse response
    /* @TODO : clean code
    /* ************************************************************************ */

    function render_html ( $atts )
    {
        extract(shortcode_atts(array(
            'id' => NULL,
            'tweets' =>NULL,
            'width' =>NULL,
            'height' =>NULL,
            'show_account' => FALSE,
            'show_actions' => FALSE,
            'show_footer' => FALSE,
            'show_avatar' => FALSE
          ), $atts));

        $featch_tweets  = $this->featch_tweets();
        $avatar         = $featch_tweets[0]->user->profile_image_url;
        $name           = $featch_tweets[0]->user->name;
        $uname          = $featch_tweets[0]->user->screen_name;
        $url            = $featch_tweets[0]->user->url;

        $id = ( !is_null( $id ) ) ? ' id="'.$id.'"' : '';
        $width = ( !is_null( $width ) ) ? ' style="width:'.$id.'"' : '';
        $height = ( !is_null( $height ) ) ? ' style="height:'.$id.'"' : '';
        $this->number_of_tweets    = ( !is_null($tweets) ) ? $tweets : $this->number_of_tweets;
?>
        <div class="twitter-wrapper media"<?=$id.$width?>>
             <div class="twitter-logo pull-left">
                  <a href="htts://twitter.com/" target="_blank"><i class="sprite sprite-icon-twtr"></i></a>
              </div>
            <div class="twitter-container media-body">
                <?php if( $show_account ) : ?>
                <div class="twitter-header">
                    <div class="twitter-inner header-inner">
                        <div class="twitter-account">
                            <a target="_blank" href="<?php echo $url ?>" title="<?php echo $name ?>">
                                <img src="<?php echo $avatar ?>" alt="<?php echo $name ?>" class="avatar">
                                <b class="fullname"><?php echo $name ?></b>
                                <small class="uname"><?php echo $uname ?></small>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="twitter-content">
                    <div class="twitter-inner"<?=$height?>>
                        <ol class="stream-items unstyled">
                            <?php for($i=0; $i<$this->number_of_tweets; $i++) :

                                $tweet = $featch_tweets[$i]->text;
                                $tweet = $this->make_links($tweet);
                                $tweet = $this->make_mentions($tweet);
                                $tweet = $this->make_hashes($tweet);

                                $tweet_id = $featch_tweets[$i]->id_str;
                                $time = $featch_tweets[$i]->created_at;
                                $tweet_url = 'https://twitter.com/'.$uname.'/status/'.$tweet_id;
                                $tweet_feed_url = 'https://twitter.com/'.$uname;
                                $tweet_url = $tweet_feed_url.'/status/'.$tweet_id;

                                $odd_even = ( $i%2 ) ? 'stream-odd' : 'stream-even';
                            ?>
                                <li class="stream-item <?php echo $odd_even; ?>">
                                    <div class="tweet row-fluid">
                                        <?php if( $show_avatar ) : ?>
                                        <div class="tweet-avatar span3">
                                            <img src="<?php echo $featch_tweets[$i]->user->profile_image_url ?> " title="<?=$featch_tweets[$i]->user->screen_name?>" class="avatar">
                                        </div>
                                        <div class="span8">
                                        <?php else : ?>
                                        <div class="span11">
                                        <?php endif ; ?>
                                        <div class="tweet-status">
                                            <a href="<?php echo $url ?>"><?php echo $featch_tweets[$i]->user->screen_name ?></a>
                                             <small class="time">
                                                <a class="tweet-timestamp" target="_blank" href="<?php echo $tweet_url ?>"><?php echo human_time_diff( strtotime($time), time() ) ?></a>
                                            </small>
                                        </div>
                                            <p class="tweet-text"><?php echo $tweet ?></p>
                                        <?php if ( $show_actions ) : ?>
                                            <ul class="tweet-actions inline">
                                                <li class="tweet-action container-reply">
                                                    <a class="btn-action btn-reply" target="_blank" href="<?php echo $tweet_url ?>">reply</a>
                                                </li>
                                                <li class="tweet-action container-retweet">
                                                    <a class="btn-action btn-retweet"  target="_blank" href="<?php echo $tweet_url ?>">retweet</a>
                                                </li>
                                                <li class="tweet-action container-favorite">
                                                    <a class="btn-action btn-favorite" target="_blank" href="<?php echo $tweet_url ?>">favorite</a>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                        </div>
                                        <div class="span1">

                                        </div>
                                    </div>
                                </li>
                            <?php endfor; ?>
                        </ol>
                    </div>
                </div>
                <?php if ( $show_footer ) : ?>
                <div class="twitter-footer">
                    <div class="twitter-link">
                        <a href="<?php echo  $tweet_feed_url ?>" target="_blank"><?php echo __('Join the conversation'); ?></a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }
}