<?php
/* 
Plugin Name: WP Social Popup and Get Traffic
Plugin URI: https://wordpress.org/plugins/wp-social-popup-and-get-traffic/
Description: Show content for likes/follow/+1/Youtube
Version: 2.1
Author: iLen
Author URI: http://es.ilentheme.com
*/
if ( !class_exists('wp_social_popup') ) {
require_once 'assets/functions/options.php';
class wp_social_popup extends wp_social_popup_make{

	function __construct(){

		parent::__construct(); // configuration general

 		if( is_admin() ){

            add_action( 'admin_enqueue_scripts', array( &$this,'ss_wp_social_popup_admin' ) );

        }elseif( ! is_admin() ) {

        	global $opt_wp_social_popup, $print_script;
            $opt_wp_social_popup = get_option( $this->parameter['name_option']."_options" ) ;

        	// ajax nonce for count visits in cache
		    if(  defined( 'WP_CACHE' ) && WP_CACHE ){

		      self::add_actions_wsp();		    	
		      add_action( 'wp_enqueue_scripts',  array( &$this, 'wp_social_popup_cache_enqueue') );
		      add_action( 'wp_ajax_nopriv_wp-social-popup', array( &$this, 'print_pop_ajax' ) );
		      add_action( 'wp_ajax_wp-social-popup', array( &$this, 'print_pop_ajax' ) );

		    }elseif( $opt_wp_social_popup[$this->parameter['name_option'].'_enabled'] ){

            	$is_mobile = self::is_MobileOrTable();

	            if( $is_mobile && $opt_wp_social_popup[$this->parameter['name_option'].'_enabled_mobiles'] ){
			        self::add_actions_wsp();
		        }elseif( !$is_mobile ){
		        	self::add_actions_wsp();
		        }

            }

        }



	}


	### Function: Calculate Post Views With WP_CACHE Enabled
	function wp_social_popup_cache_enqueue() {
	  global $post;

	  global $opt_wp_social_popup;

	  if( !defined( 'WP_CACHE' ) || !WP_CACHE )
	    return;



      wp_enqueue_script( 'wp-social-popup-cache', plugin_dir_url( __FILE__ ) . 'assets/js/spu_ajax.js' , array( 'jquery' ), $this->parameter['version'] , true );
      wp_localize_script( 'wp-social-popup-cache', 'wp_popup_cache_var', array( 'admin_ajax_url' => admin_url( 'admin-ajax.php' ), 
      																			'enable' =>  isset($opt_wp_social_popup[$this->parameter['name_option'].'_enabled'])?$opt_wp_social_popup[$this->parameter['name_option'].'_enabled']:'', 
      																			'show_close_button' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_show_close_button'])?$opt_wp_social_popup[$this->parameter['name_option'].'_show_close_button']:'',
      																			'closed_advanced_keys' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_closed_advanced_keys'])?$opt_wp_social_popup[$this->parameter['name_option'].'_closed_advanced_keys']:'',
      																			'until_popup' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_until_popup'])?$opt_wp_social_popup[$this->parameter['name_option'].'_until_popup']:'',
      																			'seconds_appear' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_seconds_appear'])?$opt_wp_social_popup[$this->parameter['name_option'].'_seconds_appear']:'',
      																			'seconds_close' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_seconds_close'])?$opt_wp_social_popup[$this->parameter['name_option'].'_seconds_close']:'',
      																			'thanks_message' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message'])?$opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message']:'',
      																			'title_message' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_title_message'])?$opt_wp_social_popup[$this->parameter['name_option'].'_title_message']:'',
      																			'content_message' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_content_message'])?$opt_wp_social_popup[$this->parameter['name_option'].'_content_message']:'',
      																			'thanks_message_seconds' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message_seconds'])?$opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message_seconds']:'',

      																			'button_fb' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_button_fb'])?$opt_wp_social_popup[$this->parameter['name_option'].'_button_fb']:'',
      																			'facebook_url_default' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_default'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_default']:'',
      																			'facebook_url_tuesday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_tuesday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_tuesday']:'',
      																			'facebook_url_wednesday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_wednesday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_wednesday']:'',
      																			'facebook_url_thursday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_thursday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_thursday']:'',
      																			'facebook_url_friday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_friday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_friday']:'',
      																			'facebook_url_saturday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_saturday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_saturday']:'',
      																			'facebook_url_sunday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_sunday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_facebook_url_sunday']:'',

      																			'button_tw' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_button_tw'])?$opt_wp_social_popup[$this->parameter['name_option'].'_button_tw']:'',
      																			'twitter_url_default' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_default'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_default']:'',
      																			'twitter_url_tuesday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_tuesday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_tuesday']:'',
      																			'twitter_url_wednesday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_wednesday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_wednesday']:'',
      																			'twitter_url_thursday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_thursday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_thursday']:'',
      																			'twitter_url_friday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_friday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_friday']:'',
      																			'twitter_url_saturday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_saturday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_saturday']:'',
      																			'twitter_url_sunday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_sunday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_sunday']:'',


      																			'button_go' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_button_go'])?$opt_wp_social_popup[$this->parameter['name_option'].'_button_go']:'',
      																			'google_url_default' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_default'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_default']:'',
      																			'google_url_tuesday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_tuesday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_tuesday']:'',
      																			'google_url_wednesday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_wednesday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_wednesday']:'',
      																			'google_url_thursday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_thursday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_thursday']:'',
      																			'google_url_friday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_friday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_friday']:'',
      																			'google_url_saturday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_saturday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_saturday']:'',
      																			'google_url_sunday' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_google_url_sunday'])?$opt_wp_social_popup[$this->parameter['name_option'].'_google_url_sunday']:'',
      																			'type_button_gplus' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_type_button_gplus'])?$opt_wp_social_popup[$this->parameter['name_option'].'_type_button_gplus']:'',
      																			'button_youtube_suscribe' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_button_youtube_suscribe'])?$opt_wp_social_popup[$this->parameter['name_option'].'_button_youtube_suscribe']:'',
      																			
      																			'opacity' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_opacity'])?$opt_wp_social_popup[$this->parameter['name_option'].'_opacity']:'',
      																			'enabled_mobiles' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_enabled_mobiles'])?$opt_wp_social_popup[$this->parameter['name_option'].'_enabled_mobiles']:'',
      																			'show_in' =>isset($opt_wp_social_popup[$this->parameter['name_option'].'_show_in'])?$opt_wp_social_popup[$this->parameter['name_option'].'_show_in']:'',
      																			'type_page_current' => self::TypePost(),
      																			
      																		) );


	}


	function TypePost(){

		if( is_home() )
			return 'home';
		elseif( in_array( $type = get_post_type() , array('post','page') ) )
			return $type;
		else
			return '';

	}
 



 
	//--PLUGIN------------------------ ---------------
	function print_scripts(){
		//code 
		
		global $opt_wp_social_popup,$print_script;
 
		if( defined( 'WP_CACHE' ) && WP_CACHE ){
 
			add_action('wp_enqueue_scripts', array( &$this,'ss_wp_social_popup') );
		}else{

			$array_show_in = $opt_wp_social_popup[$this->parameter['name_option'].'_show_in'];
			$print_script = false;

			if( in_array( 'everywhere', $array_show_in ) ){
	 
					$print_script = true;

			} 
			if( in_array( 'home' , $array_show_in ) && $print_script == false ){

				if( is_home() || is_front_page() ){

					$print_script = true;

				}

			} 
			if( in_array( 'post' , $array_show_in ) && $print_script == false ){

				if( is_single() ){

					$print_script = true;

				}

			} 
			if( in_array( 'page' , $array_show_in ) && $print_script == false ){

				if( is_page() ){

					$print_script = true;

				}

			}
			

			if( $print_script == true ) {
	            add_action('wp_enqueue_scripts', array( &$this,'ss_wp_social_popup') );

	        }
	    }

	}


	

 



	/**
	* Load scripts and styles
	*/
	function ss_wp_social_popup(){
			wp_enqueue_script('wsp-fb', 'http://connect.facebook.net/en_US/all.js#xfbml=1', array('jquery'),$this->parameter['version'],FALSE);
			wp_enqueue_script('wsp-tw', 'http://platform.twitter.com/widgets.js', array('jquery'),$this->parameter['version'],FALSE);
			wp_enqueue_script('wsp-social', plugins_url( 'assets/js/spu.js' , __FILE__ ),array('jquery'),$this->parameter['version']);
			wp_enqueue_style('wsp-css', plugins_url( 'assets/css/spu.css' , __FILE__ ),'all',$this->parameter['version']);
            
            if(  ! defined( 'WP_CACHE' ) || ! WP_CACHE ){
                wp_enqueue_script('wsp-go', 'https://apis.google.com/js/plusone.js', array('jquery'),$this->parameter['version'],FALSE);
            }
	}

	/**
	* Load scripts and styles in footer
	*/
	function print_scripts_footer(){
		global $opt_wp_social_popup,$print_script;
		$credit = isset($opt_wp_social_popup[$this->parameter['name_option'].'_credits'])?$opt_wp_social_popup[$this->parameter['name_option'].'_credits']:'';

		if( $print_script ){
		?>							
		<script type="text/javascript">
			jQuery(document).ready(function($){
			setTimeout( 
			function(){				
				socialPopupTrafic({
					// Configure display of popup
					advancedClose: <?php echo ($opt_wp_social_popup[$this->parameter['name_option'].'_closed_advanced_keys']?'true':'false'); ?>,
					opacity: "<?php echo $opt_wp_social_popup[$this->parameter['name_option'].'_opacity']; ?>",
					s_to_close: "<?php echo $opt_wp_social_popup[$this->parameter['name_option'].'_seconds_close']; ?>",
					days_no_click: "<?php echo $opt_wp_social_popup[$this->parameter['name_option'].'_until_popup']; ?>",
					segundos : "<?php echo 'seconds'; ?>",
					esperar : "<?php echo 'Wait'; ?>",
					thanks_msg : "<?php echo $opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message'];  ?>",
					thanks_sec : "<?php echo $opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message_seconds']; ?>"
				})
			}
				,<?php echo (int)$opt_wp_social_popup[$this->parameter['name_option'].'_seconds_appear'] * 1000 ;?>
					);
			});	
		</script>
						
		<?php
		}
	}

    function ss_wp_social_popup_admin(){
        wp_enqueue_script( 'wp_social_popup_js', plugins_url('assets/js/plugin.js',__FILE__), array( 'jquery' ), '1.0', true );
        wp_enqueue_style( 'wp_social_popup_css_admin', plugins_url('assets/css/admin.css',__FILE__),'all',$this->parameter['version']);
    }

    /**
	* Print popup html markup in footer
	*/
	function print_pop()
	{
		global $opt_wp_social_popup,$print_script;
		$credit = isset($opt_wp_social_popup[$this->parameter['name_option'].'_credits'])?$opt_wp_social_popup[$this->parameter['name_option'].'_credits']:'';

 		if( $print_script ){
	  	$socials = array();
	  	$suf_day = self::getItembyDay();

	  	$social_button_set = "";
	  	$socials["youtube"] = "";
	  	$socials["google"] = "";
	  	$socials["twitter"] = "";
	  	$socials["facebook"] = "";
        
        
        //--YO
	  	if( isset( $opt_wp_social_popup[$this->parameter['name_option']."_button_youtube_suscribe"] ) && $opt_wp_social_popup[$this->parameter['name_option']."_button_youtube_suscribe"] ){

		  	 $socials["youtube"] ='<div class="g-ytsubscribe" data-channel="'.$opt_wp_social_popup[$this->parameter['name_option']."_button_youtube_suscribe"].'" data-layout="full" data-count="undefined"></div>';
 
 		}
        
	  	//--GO
	  	if( $opt_wp_social_popup[$this->parameter['name_option']."_button_go"] == '1' ){
	  		$type_g = $opt_wp_social_popup[$this->parameter['name_option']."_type_button_gplus"] == "button"?"g-plusone":"g-plus";
		  	if( ! $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_google_url_{$suf_day}"] )
	  			if( $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_google_url_default"] )
	  				$socials["google"] ='<div class="spu-button spu-google"><div class="'.$type_g.'" data-callback="googleCB" data-onendinteraction="closeGoogle" data-recommendations="false" data-annotation="bubble" data-size="medium" data-href="' . $social_button_set . '" width="300" height="69"></div></div>'; 
	  			else
	  				$socials["google"] = "";
	  		else
	  			$socials["google"] ='<div class="spu-button spu-google"><div class="'.$type_g.'" data-callback="googleCB" data-onendinteraction="closeGoogle" data-recommendations="false" data-annotation="bubble" data-size="medium" data-href="' . $social_button_set . '" width="300" height="69"></div></div>'; 
 		}

  		//--TW
	  	$social_button_set = "";
	  	if( $opt_wp_social_popup[$this->parameter['name_option']."_button_tw"] == '1' )
		  	if( ! $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_twitter_url_{$suf_day}"] )
	  			if( $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_twitter_url_default"] )
	  				$socials["twitter"] ='<div class="spu-button spu-twitter"><a href="https://twitter.com/' . $opt_wp_social_popup[$this->parameter['name_option'].'_twitter_url_default'] . '" class="twitter-follow-button" data-show-count="false" >Follow Me</a></div>'; 
	  			else
	  				$socials["twitter"] = "";
	  		else
	  			$socials["twitter"] ='<div class="spu-button spu-twitter"><a href="https://twitter.com/' . $social_button_set . '" class="twitter-follow-button" data-show-count="false" >Follow Me</a></div>'; 

  		//--FB
	  	$social_button_set = "";
	  	if( $opt_wp_social_popup[$this->parameter['name_option']."_button_fb"] == '1' )
		  	if( ! $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_facebook_url_{$suf_day}"] )
	  			if( $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_facebook_url_default"] )
	  				$socials["facebook"] = '<div class="spu-button spu-facebook"><div id="fb-root"></div><div class="fb-like" data-href="' . $social_button_set . '" data-send="false" data-width="450" data-show-faces="true"data-layout="button_count"></div></div>'; 
	  			else
	  				$socials["facebook"] = "";
	  		else
	  			$socials["facebook"] = '<div class="spu-button spu-facebook"><div id="fb-root"></div><div class="fb-like" data-href="' . $social_button_set . '" data-send="false" data-width="450" data-show-faces="true"data-layout="button_count"></div></div>'; 

		echo '<div id="spu-bg"></div>
				<div id="spu-main">';
				echo $opt_wp_social_popup[$this->parameter['name_option'].'_show_close_button'] ? '<a href="#" onClick="spuFlush('. $opt_wp_social_popup[$this->parameter['name_option'].'_until_popup'] .');" id="spu-close">✕</a>' : '';
				echo '<div id="spu-body">';
				echo "<div id='spu-title'>".$opt_wp_social_popup[$this->parameter['name_option'].'_title_message']."</div>
						<div id='spu-msg-cont'>
						     <div id='spu-msg'>
						     ".$opt_wp_social_popup[$this->parameter['name_option'].'_content_message']."
						     <br /><br />
						     {$socials["youtube"]} {$socials["twitter"]} {$socials["facebook"]} {$socials["google"]}
						     </div>
						    <div class='step-clear'></div>
					  </div>";
				echo '<span id="spu-timer"></span>';
		echo ( $credit ) ? '<div id="spu-bottom"><span style="font-size:10px;float: right;margin-top: -6px;">Social Popup by <a href="http://www.ilentheme.com">iLen</a></span></div>':'';
				echo "</div>";
		echo '</div>';
		echo "<input type='hidden' name='hd_msg_thanks' id='hd_msg_thanks' value='".$opt_wp_social_popup[$this->parameter['name_option'].'_thanks_message']."' />";
		echo "<style>#spu-bg{background:#fff;}#spu-main{ }</style>";
		}
	}

	/**
	* Print popup html markup in footer AJAX
	*/
	function print_pop_ajax()
	{ 
	   global $opt_wp_social_popup;
       $type_g = $opt_wp_social_popup[$this->parameter['name_option']."_type_button_gplus"] == "button"?"g-plusone":"g-plus";
       $social_button_set = $opt_wp_social_popup[$this->parameter['name_option']."_google_url_default"];
        echo ' <!-- Inserta esta etiqueta en la sección "head" o justo antes de la etiqueta "body" de cierre. -->
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: "es", parsetags: "explicit"}
</script>';
        echo '<div class="put_gplus" style="display:none"><div class="'.$type_g.'" data-callback="googleCB" data-onendinteraction="closeGoogle" data-recommendations="false" data-annotation="bubble" data-size="medium" data-href="'.$social_button_set.'" width="300" height="69"></div></div>';
        echo '<div class="put_youtube" style="display:none"><div class="g-ytsubscribe" data-channel="'.$opt_wp_social_popup[$this->parameter['name_option']."_button_youtube_suscribe"].'" data-layout="full" data-count="undefined"></div></div>';
		echo "<div id='wp_social_popup_and_get_traffic'></div>";

		 
	}


	function getItembyDay(){	
		//code 
		$dayNames = array(
		    1=>'default',
		    2=>'tuesday', 
		    3=>'wednesday', 
		    4=>'thursday', 
		    5=>'friday', 
		    6=>'saturday', 
		    7=>'sunday', 
		 );
		//$dw = date( "w", time());
		$daynum = date("N", time());

		return $dayNames[ $daynum ];
		
	}


	function is_MobileOrTable(){
		

		require_once "assets/ilenframework/assets/lib/Mobile_Detect.php";

		$detect = new Mobile_Detect;

		if( $detect->isMobile() || $detect->isTablet() )
		 	return true;
		else
			return false;

	}

	function add_actions_wsp(){
		if(  ! defined( 'WP_CACHE' ) || ! WP_CACHE ){
			add_action( 'wp_footer', array( &$this,'print_scripts_footer'),13);
			add_action( 'wp_footer',array(&$this,'print_pop' ),14 );	
		}else{
			add_action( 'wp_footer',array(&$this,'print_pop_ajax' ) );
		}
		
	    add_action('template_redirect', array(&$this,'print_scripts'), 12 );
		
	}




} // end class
} // end if
global $IF_CONFIG;
unset($IF_CONFIG);
$IF_CONFIG = null;
$IF_CONFIG = new wp_social_popup;
require_once "assets/ilenframework/core.php";
?>