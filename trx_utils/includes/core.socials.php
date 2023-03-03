<?php
/**
 * Grace-Church Framework: social networks
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('grace_church_socials_theme_setup')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_socials_theme_setup' );
	function grace_church_socials_theme_setup() {

		if ( !is_admin() ) {
			// Add og:image meta tag for facebook
			add_action( 'wp_head', 					'grace_church_facebook_og_tags', 5 );
		} else {
            // Add extra fields in the user profile
            add_action( 'show_user_profile',		'grace_church_add_fields_in_user_profile' );
            add_action( 'edit_user_profile',		'grace_church_add_fields_in_user_profile' );

            // Save / update additional fields from profile
            add_action( 'personal_options_update',	'grace_church_save_fields_in_user_profile' );
            add_action( 'edit_user_profile_update',	'grace_church_save_fields_in_user_profile' );
        }
	}
}


/* Social Share and Profile links
-------------------------------------------------------------------------------- */

if (!function_exists('grace_church_socials_theme_setup1')) {
    add_action( 'grace_church_action_before_init_theme', 'grace_church_socials_theme_setup1' );
    function grace_church_socials_theme_setup1() {
	
    // List of social networks for site sharing and user profiles
    $GRACE_CHURCH_GLOBALS['share_links'] = array(
        'blogger' =>		'http://www.blogger.com/blog_this.pyra?t&u={link}&n={title}',
        'bobrdobr' =>		'http://bobrdobr.ru/add.html?url={link}&title={title}&desc={descr}',
        'delicious' =>		'http://delicious.com/save?url={link}&title={title}&note={descr}',
        'designbump' =>		'http://designbump.com/node/add/drigg/?url={link}&title={title}',
        'designfloat' =>	'http://www.designfloat.com/submit.php?url={link}',
        'digg' =>			'http://digg.com/submit?url={link}',
        'evernote' =>		'https://www.evernote.com/clip.action?url={link}&title={title}',
        'facebook' =>		'http://www.facebook.com/sharer.php?s=100&p[url]={link}&p[title]={title}&p[summary]={descr}&p[images][0]={image}',
        'friendfeed' =>		'http://www.friendfeed.com/share?title={title} - {link}',
        'google' =>			'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&annotation={descr}',
        'gplus' => 			'https://plus.google.com/share?url={link}',
        'identi' => 		'http://identi.ca/notice/new?status_textarea={title} - {link}',
        'juick' => 			'http://www.juick.com/post?body={title} - {link}',
        'linkedin' => 		'http://www.linkedin.com/shareArticle?mini=true&url={link}&title={title}',
        'liveinternet' =>	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl={link}&cntitle={title}',
        'livejournal' =>	'http://www.livejournal.com/update.bml?event={link}&subject={title}',
        'mail' =>			'http://connect.mail.ru/share?url={link}&title={title}&description={descr}&imageurl={image}',
        'memori' =>			'http://memori.ru/link/?sm=1&u_data[url]={link}&u_data[name]={title}',
        'mister-wong' =>	'http://www.mister-wong.ru/index.php?action=addurl&bm_url={link}&bm_description={title}',
        'mixx' =>			'http://chime.in/chimebutton/compose/?utm_source=bookmarklet&utm_medium=compose&utm_campaign=chime&chime[url]={link}&chime[title]={title}&chime[body]={descr}',
        'moykrug' =>		'http://share.yandex.ru/go.xml?service=moikrug&url={link}&title={title}&description={descr}',
        'myspace' =>		'http://www.myspace.com/Modules/PostTo/Pages/?u={link}&t={title}&c={descr}',
        'newsvine' =>		'http://www.newsvine.com/_tools/seed&save?u={link}&h={title}',
        'odnoklassniki' =>	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={link}&title={title}',
        'pikabu' =>			'http://pikabu.ru/add_story.php?story_url={link}',
        'pinterest' =>		'json:{"link": "http://pinterest.com/pin/create/button/", "script": "//assets.pinterest.com/js/pinit.js", "style": "", "attributes": {"data-pin-do": "buttonPin", "data-pin-media": "{image}", "data-pin-url": "{link}", "data-pin-description": "{title}", "data-pin-custom": "true","nopopup": "true"}}',
        'posterous' =>		'http://posterous.com/share?linkto={link}&title={title}',
        'postila' =>		'http://postila.ru/publish/?url={link}&agregator=grace_church',
        'reddit' =>			'http://reddit.com/submit?url={link}&title={title}',
        'rutvit' =>			'http://rutvit.ru/tools/widgets/share/popup?url={link}&title={title}',
        'stumbleupon' =>	'http://www.stumbleupon.com/submit?url={link}&title={title}',
        'surfingbird' =>	'http://surfingbird.ru/share?url={link}',
        'technorati' =>		'http://technorati.com/faves?add={link}&title={title}',
        'tumblr' =>			'http://www.tumblr.com/share?v=3&u={link}&t={title}&s={descr}',
        'twitter' =>		'https://twitter.com/intent/tweet?text={title}&url={link}',
        'vk' =>				'http://vk.com/share.php?url={link}&title={title}&description={descr}',
        'vk2' =>			'http://vk.com/share.php?url={link}&title={title}&description={descr}',
        'webdiscover' =>	'http://webdiscover.ru/share.php?url={link}',
        'yahoo' =>			'http://bookmarks.yahoo.com/toolbar/savebm?u={link}&t={title}&d={descr}',
        'yandex' =>			'http://zakladki.yandex.ru/newlink.xml?url={link}&name={title}&descr={descr}',
        'ya' =>				'http://my.ya.ru/posts_add_link.xml?URL={link}&title={title}&body={descr}',
        'yosmi' =>			'http://yosmi.ru/index.php?do=share&url={link}'
        );
    }
}

// Add social network
// Example: 1) add_share_link('pinterest', 'url');
//			2) add_share_link(array('pinterest'=>'url', 'dribble'=>'url'));
if (!function_exists('grace_church_add_share_link')) {
	function grace_church_add_share_link($soc, $url='') {
		if (!is_array($soc)) $soc = array($soc => $url);
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['share_links'] = array_merge( $GRACE_CHURCH_GLOBALS['share_links'], $soc );
	}
}

// Return (and show) share social links
if (!function_exists('grace_church_show_share_links')) {
	function grace_church_show_share_links($args) {
		if ( grace_church_get_custom_option('show_share')=='hide' ) return '';

		$args = array_merge(array(
			'post_id' => 0,						// post ID
			'post_link' => '',					// post link
			'post_title' => '',					// post title
			'post_descr' => '',					// post descr
			'post_thumb' => '',					// post featured image
			'size' => 'small',					// icons size: tiny|small|big
			'style' => grace_church_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons',	// style for show icons: icons|images|bg
			'type' => 'block',					// share block type: list|block|drop
			'popup' => true,					// open share url in new window or in popup window
			'counters' => grace_church_get_custom_option('show_share_counters')=='yes',	// show share counters
			'direction' => grace_church_get_custom_option('show_share'),				// share block direction
			'caption' => grace_church_get_custom_option('share_caption'),				// share block caption
			'share' => grace_church_get_theme_option('share_buttons'),					// list of allowed socials
			'echo' => true						// if true - show on page, else - only return as string
			), $args);

		if (count($args['share'])==0 || implode('', $args['share'][0])=='') return '';

		global $GRACE_CHURCH_GLOBALS;

		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];


		$output = '<div class="sc_socials sc_socials_size_'.esc_attr($args['size']).' sc_socials_share' . ($args['type']=='drop' ? ' sc_socials_drop' : ' sc_socials_dir_' . esc_attr($args['direction'])) . '">'
			. ($args['caption']!='' ? '<span class="share_caption">'.($args['caption']).'</span>' : '');

		if (is_array($args['share']) && count($args['share']) > 0) {
			foreach ($args['share'] as $soc) {
				$icon = $args['style']=='icons' || grace_church_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : grace_church_get_socials_url(basename($soc['icon']));
				if ($args['style'] == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $parts[0];
				} else {
					$sn = basename($soc['icon']);
					$sn = grace_church_substr($sn, 0, grace_church_strrpos($sn, '.'));
					if (($pos=grace_church_strrpos($sn, '_'))!==false)
						$sn = grace_church_substr($sn, 0, $pos);
				}
				$url = empty($soc['url']) && !empty($GRACE_CHURCH_GLOBALS['share_links'][$sn]) ? $GRACE_CHURCH_GLOBALS['share_links'][$sn] : $soc['url'];

                if (substr($url, 0, 5) == 'json:') {
                    $url = json_decode(substr($url, 5), true);
                    if (is_null($url))
                        continue;
                }

                $link = str_replace(
					array('{id}', '{link}', '{title}', '{descr}', '{image}'),
					array(
						urlencode($args['post_id']),
						urlencode($args['post_link']),
						urlencode(strip_tags($args['post_title'])),
						urlencode(strip_tags($args['post_descr'])),
						urlencode($args['post_thumb'])
						),
                    is_array($url) ? $url['link'] : $url);
				$output .= '<div class="sc_socials_item">'
						. '<a href="'.esc_url($soc['url']).'"'
						. ' class="social_icons social_'.esc_attr($sn).'"'
						. ($args['popup'] ? ' onclick="window.open(\'' . esc_url($link) .'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=480, height=400, toolbar=0, status=0\'); return false;"' : ' target="_blank"')
						. ($args['style']=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
						. ($args['counters'] ? ' data-count="'.esc_attr($sn).'"' : '');

                if ( is_array($url)) {
                    foreach($url['attributes'] as $k=>$v) {
                        $v = str_replace(
                            array('{id}', '{link}', '{title}', '{descr}', '{image}'),
                            array(
                                $k=='href' ? urlencode($args['post_id']) : $args['post_id'],
                                $k=='href' ? urlencode($args['post_link']) : $args['post_link'],
                                $k=='href' ? urlencode(strip_tags($args['post_title'])) : strip_tags($args['post_title']),
                                $k=='href' ? urlencode(strip_tags($args['post_descr'])) : strip_tags($args['post_descr']),
                                $k=='href' ? urlencode($args['post_thumb']) : $args['post_thumb']
                            ),
                            $v);
                        $output .= " {$k}=\"" . ($k=='href' ? esc_url($v) : esc_attr($v)) . '"';
                    }
                }

                $output .= '>'
					. ($args['style']=='icons'
						? '<span class="' . esc_attr($soc['icon']) . '"></span>'
						: ($args['style']=='images'
							? '<img src="'.esc_url($icon).'" alt="'.esc_attr($sn).'" />'
							: '<span class="sc_socials_hover" style="background-image: url('.esc_url($icon).');"></span>'
							)
						)
					. '</a>'
					. ($args['type']=='drop' ? '<i>' . trim(grace_church_strtoproper($sn)) . '</i>' : '')
					. '</div>';
                if (!empty($url['script'])){
                    wp_enqueue_script( "trx_addons_share_{$sn}", $url['script'], array(), null, true );
                }
            }
		}
		$output .= '</div>';
		if ($args['echo']) echo ($output);
		return $output;
	}
}


// Return social icons links
if (!function_exists('grace_church_prepare_socials')) {
	function grace_church_prepare_socials($list, $style='') {
		if ($style == '' || is_array($style)){
		    $style = grace_church_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons';
        }
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		if (is_array($list) && count($list) > 0) {
			foreach ($list as $soc) {
				if (empty($soc['url'])) continue;
				$icon = $style=='icons' || grace_church_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : grace_church_get_socials_url(basename($soc['icon']));
				if ($style == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $parts[0];
				} else {
					$sn = basename($soc['icon']);
					$sn = grace_church_substr($sn, 0, grace_church_strrpos($sn, '.'));
					if (($pos=grace_church_strrpos($sn, '_'))!==false)
						$sn = grace_church_substr($sn, 0, $pos);
				}
				$output .= '<div class="sc_socials_item">'
						. '<a href="'.esc_url($soc['url']).'" target="_blank" class="social_icons social_'.esc_attr($sn).'"'
						. ($style=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
						. '>'
						. ($style=='icons' 
							? '<span class="icon-' . esc_attr($sn) . '"></span>' 
							: ($style=='images' 
								? '<img src="'.esc_url($icon).'" alt="" />' 
								: '<span class="sc_socials_hover" style="background-image: url('.esc_url($icon).');"></span>'))
						. '</a>'
						. '</div>';
			}
		}
		return $output;
	}
}
	
	
/* Twitter
-------------------------------------------------------------------------------- */

if (!function_exists('grace_church_get_twitter_data')) {
    function grace_church_get_twitter_data($cfg) {
        return function_exists('trx_utils_twitter_acquire_data')
            ? trx_utils_twitter_acquire_data(array(
                'mode'            => 'user_timeline',
                'consumer_key'    => $cfg['consumer_key'],
                'consumer_secret' => $cfg['consumer_secret'],
                'token'           => $cfg['token'],
                'secret'          => $cfg['secret']
            ))
            : '';
    }
}

if (!function_exists('grace_church_prepare_twitter_text')) {
	function grace_church_prepare_twitter_text($tweet) {
		$text = $tweet['text'];
		if (!empty($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {
			foreach ($tweet['entities']['urls'] as $url) {
				$text = str_replace($url['url'], '<a href="'.esc_url($url['expanded_url']).'" target="_blank">' . ($url['display_url']) . '</a>', $text);
			}
		}
		if (!empty($tweet['entities']['media']) && count($tweet['entities']['media']) > 0) {
			foreach ($tweet['entities']['media'] as $url) {
				$text = str_replace($url['url'], '<a href="'.esc_url($url['expanded_url']).'" target="_blank">' . ($url['display_url']) . '</a>', $text);
			}
		}
		return $text;
	}
}

// Return Twitter followers count
if (!function_exists('grace_church_get_twitter_followers')) {
	function grace_church_get_twitter_followers($cfg) {
		$data = grace_church_get_twitter_data($cfg);
		return $data && isset($data[0]['user']['followers_count']) ? $data[0]['user']['followers_count'] : 0;
	}
}



/* Facebook
-------------------------------------------------------------------------------- */

if (!function_exists('grace_church_get_facebook_likes')) {
	function grace_church_get_facebook_likes($account) {
		$fb = get_transient("facebooklikes");
		if ($fb !== false) return $fb;
		$fb = '?';
		$url = esc_url(grace_church_get_protocol().'http://graph.facebook.com/'.($account));
		$headers = get_headers($url);
		if (grace_church_strpos($headers[0], '200')) {
			$json = grace_church_fgc($url);
			$rez = json_decode($json, true);
			if (isset($rez['likes']) ) {
				$fb = $rez['likes'];
				set_transient("facebooklikes", $fb, 60*60);
			}
		}
		return $fb;
	}
}


// Add facebook meta tags for post/page sharing
function grace_church_facebook_og_tags() {
	global $post;
	if ( !is_singular() || grace_church_get_global('blog_streampage')) return;
	if (has_post_thumbnail( $post->ID )) {
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>' . "\n";
	}
}


/* Feedburner
-------------------------------------------------------------------------------- */

if (!function_exists('grace_church_get_feedburner_counter')) {
	function grace_church_get_feedburner_counter($account) {
		$rss = get_transient("feedburnercounter");
		if ($rss !== false) return $rss;
		$rss = '?';
		$url = esc_url(grace_church_get_protocol().'://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.($account));
		$headers = get_headers($url);
		if (grace_church_strpos($headers[0], '200')) {
			$xml = grace_church_fgc($url);
			preg_match('/circulation="(\d+)"/', $xml, $match);
			if ($match[1] != 0) {
				$rss = $match[1];
				set_transient("feedburnercounter", $rss, 60*60);
			}
		}
		return $rss;
	}
}

// Show additional fields in the user profile
if (!function_exists('grace_church_add_fields_in_user_profile')) {
    function grace_church_add_fields_in_user_profile( $user ) {
        ?>
        <h3><?php esc_html_e('User Position', 'trx_utils'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="user_position"><?php esc_html_e('User position', 'trx_utils'); ?>:</label></th>
                <td><input type="text" name="user_position" id="user_position" size="55" value="<?php echo esc_attr(get_the_author_meta('user_position', $user->ID)); ?>" />
                    <span class="description"><?php esc_html_e('Please, enter your position in the company', 'trx_utils'); ?></span>
                </td>
            </tr>
        </table>

        <h3><?php esc_html_e('Social links', 'trx_utils'); ?></h3>
        <table class="form-table">
            <?php
            $socials_type = grace_church_get_theme_setting('socials_type');
            $social_list = grace_church_get_theme_option('social_icons');
            if (is_array($social_list) && count($social_list) > 0) {
                foreach ($social_list as $soc) {
                    if ($socials_type == 'icons') {
                        $parts = explode('-', $soc['icon'], 2);
                        $sn = isset($parts[1]) ? $parts[1] : $sn;
                    } else {
                        $sn = basename($soc['icon']);
                        $sn = grace_church_substr($sn, 0, grace_church_strrpos($sn, '.'));
                        if (($pos=grace_church_strrpos($sn, '_'))!==false)
                            $sn = grace_church_substr($sn, 0, $pos);
                    }
                    if (!empty($sn)) {
                        ?>
                        <tr>
                            <th><label for="user_<?php echo esc_attr($sn); ?>"><?php grace_church_show_layout(grace_church_strtoproper($sn)); ?>:</label></th>
                            <td><input type="text" name="user_<?php echo esc_attr($sn); ?>" id="user_<?php echo esc_attr($sn); ?>" size="55" value="<?php echo esc_attr(get_the_author_meta('user_'.($sn), $user->ID)); ?>" />
                                <span class="description"><?php echo sprintf( esc_html__('Please, enter your %s link', 'trx_utils'), grace_church_strtoproper($sn)); ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </table>
        <?php
    }
}

//Return Post Views Count
if (!function_exists('trx_utils_get_post_views')) {
    add_filter('trx_utils_filter_get_post_views', 'trx_utils_get_post_views', 10, 2);
    function trx_utils_get_post_views($default=0, $id=0){
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = 'post_views_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, '0');
            $count = 0;
        }
        return $count;
    }
}

//Set Post Views Count
if (!function_exists('trx_utils_set_post_views')) {
    add_action('trx_utils_filter_set_post_views', 'trx_utils_set_post_views', 10, 2);
    function trx_utils_set_post_views($id=0, $counter=-1) {
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = 'post_views_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, 1);
        } else {
            $count = $counter >= 0 ? $counter : $count+1;
            update_post_meta($id, $count_key, $count);
        }
    }
}

//Return Post Likes Count
if (!function_exists('trx_utils_get_post_likes')) {
    add_filter('trx_utils_filter_get_post_likes', 'trx_utils_get_post_likes', 10, 2);
    function trx_utils_get_post_likes($default=0, $id=0){
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = 'post_likes_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, '0');
            $count = 0;
        }
        return $count;
    }
}

//Set Post Likes Count
if (!function_exists('trx_utils_set_post_likes')) {
    add_action('trx_utils_filter_set_post_likes', 'trx_utils_set_post_likes', 10, 2);
    function trx_utils_set_post_likes($id=0, $count=0) {
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = 'post_likes_count';
        update_post_meta($id, $count_key, $count);
    }
}


// AJAX: Set post likes/views count
// Handler of add_action('wp_ajax_post_counter', 			'trx_utils_callback_post_counter');
// Handler of add_action('wp_ajax_nopriv_post_counter',	'trx_utils_callback_post_counter');
if ( !function_exists( 'trx_utils_callback_post_counter' ) ) {
    function trx_utils_callback_post_counter() {
        global $_REQUEST, $GRACE_CHURCH_GLOBALS;

        if ( !wp_verify_nonce( $_REQUEST['nonce'], $GRACE_CHURCH_GLOBALS['ajax_url'] ) )
            wp_die();

        $response = array('error'=>'');

        $id = (int) trx_utils_get_value_gp('post_id');
        if (isset($_REQUEST['likes'])) {
            $counter = max(0, (int) trx_utils_get_value_gp('likes'));
            trx_utils_set_post_likes($id, $counter);
        } else if (isset($_REQUEST['views'])) {
            $counter = max(0, (int) trx_utils_get_value_gp('views'));
            trx_utils_set_post_views($id, $counter);
        }
        echo json_encode($response);
        wp_die();
    }
}

// Save / update additional fields
if (!function_exists('grace_church_save_fields_in_user_profile')) {
    function grace_church_save_fields_in_user_profile( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;
        update_user_meta( $user_id, 'user_position', $_POST['user_position'] );
        $socials_type = grace_church_get_theme_setting('socials_type');
        $social_list = grace_church_get_theme_option('social_icons');
        if (is_array($social_list) && count($social_list) > 0) {
            foreach ($social_list as $soc) {
                if ($socials_type == 'icons') {
                    $parts = explode('-', $soc['icon'], 2);
                    $sn = isset($parts[1]) ? $parts[1] : $sn;
                } else {
                    $sn = basename($soc['icon']);
                    $sn = grace_church_substr($sn, 0, grace_church_strrpos($sn, '.'));
                    if (($pos=grace_church_strrpos($sn, '_'))!==false)
                        $sn = grace_church_substr($sn, 0, $pos);
                }
                update_user_meta( $user_id, 'user_'.($sn), $_POST['user_'.($sn)] );
            }
        }
    }
}
?>