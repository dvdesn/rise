<?php
/**
 * Grace-Church Framework: file system manipulations, styles and scripts usage, etc.
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* File system initialization
------------------------------------------------------------------------------------- */

// Init WP Filesystem
if (!function_exists('grace_church_init_filesystem')) {
    add_action( 'after_setup_theme', 'grace_church_init_filesystem', 0);
    function grace_church_init_filesystem() {
        if( !function_exists('WP_Filesystem') ) {
            require_once( ABSPATH .'/wp-admin/includes/file.php' );
        }
        if (is_admin()) {
            $url = admin_url();
            $creds = false;
            // First attempt to get credentials.
            if ( function_exists('request_filesystem_credentials') && false === ( $creds = request_filesystem_credentials( $url, '', false, false, array() ) ) ) {
                // If we comes here - we don't have credentials
                // so the request for them is displaying no need for further processing
                return false;
            }

            // Now we got some credentials - try to use them.
            if ( !WP_Filesystem( $creds ) ) {
                // Incorrect connection data - ask for credentials again, now with error message.
                if ( function_exists('request_filesystem_credentials') ) request_filesystem_credentials( $url, '', true, false );
                return false;
            }

            return true; // Filesystem object successfully initiated.
        } else {
            WP_Filesystem();
        }
        return true;
    }
}


// Get text from specified file
if (!function_exists('themerex_fgc')) {
    function themerex_fgc($file) {
        static $allow_url_fopen = -1;
        if ($allow_url_fopen==-1) $allow_url_fopen = (int) ini_get('allow_url_fopen');
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return !$allow_url_fopen && strpos($file, '//')!==false
                    ? themerex_remote_get($file)
                    : $wp_filesystem->get_contents($file);
            } else {
                if (themerex_param_is_on(themerex_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get contents from the file "%s" failed', 'grace-church'), $file));
            }
        }
        return '';
    }
}


// Put data into specified file
if (!function_exists('grace_church_fpc')) {
    function grace_church_fpc($file, $data, $flag=0) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                // Attention! WP_Filesystem can't append the content to the file!
                // That's why we have to read the contents of the file into a string,
                // add new content to this string and re-write it to the file if parameter $flag == FILE_APPEND!
                return $wp_filesystem->put_contents($file, ($flag==FILE_APPEND ? $wp_filesystem->get_contents($file) : '') . $data, false);
            } else {
                if (grace_church_sc_param_is_on(grace_church_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Put contents to the file "%s" failed', 'grace-church'), $file));
            }
        }
        return false;
    }
}

// Get text from specified file
if (!function_exists('grace_church_fgc')) {
    function grace_church_fgc($file) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return $wp_filesystem->get_contents($file);
            } else {
                if (grace_church_sc_param_is_on(grace_church_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get contents from the file "%s" failed', 'grace-church'), $file));
            }
        }
        return '';
    }
}

// Get array with rows from specified file
if (!function_exists('grace_church_fga')) {
    function grace_church_fga($file) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return $wp_filesystem->get_contents_array($file);
            } else {
                if (grace_church_sc_param_is_on(grace_church_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get rows from the file "%s" failed', 'grace-church'), $file));
            }
        }
        return array();
    }
}

// Remove unsafe characters from file/folder path
if (!function_exists('grace_church_esc')) {
    function grace_church_esc($file) {
        return sanitize_file_name($file);
    }
}




/* File names manipulations
------------------------------------------------------------------------------------- */

// Return path to directory with uploaded images
if (!function_exists('grace_church_get_uploads_dir_from_url')) {
	function grace_church_get_uploads_dir_from_url($url) {
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		if (!strncmp($url, $https_prefix, grace_church_strlen($https_prefix)))			//if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		else if (!strncmp($url, $http_prefix, grace_church_strlen($http_prefix)))		//if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
	
		// Check if $img_url is local.
		if ( false === grace_church_strpos( $url, $upload_url ) ) return false;
	
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = ($upload_dir) . ($rel_path);
		
		return $img_path;
	}
}

// Replace uploads url to current site uploads url
if (!function_exists('grace_church_replace_uploads_url')) {
    function grace_church_replace_uploads_url($str, $uploads_folder='uploads') {
        static $uploads_url = '', $uploads_len = 0;
        if (is_array($str) && count($str) > 0) {
            foreach ($str as $k=>$v) {
                $str[$k] = grace_church_replace_uploads_url($v, $uploads_folder);
            }
        } else if (is_string($str)) {
            if (empty($uploads_url)) {
                $uploads_info = wp_upload_dir();
                $uploads_url = $uploads_info['baseurl'];
                $uploads_len = grace_church_strlen($uploads_url);
            }
            $break = '\'" ';
            $pos = 0;
            while (($pos = grace_church_strpos($str, "/{$uploads_folder}/", $pos))!==false) {
                $pos0 = $pos;
                $chg = true;
                while ($pos0) {
                    if (grace_church_strpos($break, grace_church_substr($str, $pos0, 1))!==false) {
                        $chg = false;
                        break;
                    }
                    if (grace_church_substr($str, $pos0, 5)=='http:' || grace_church_substr($str, $pos0, 6)=='https:')
                        break;
                    $pos0--;
                }
                if ($chg) {
                    $str = ($pos0 > 0 ? grace_church_substr($str, 0, $pos0) : '') . ($uploads_url) . grace_church_substr($str, $pos+grace_church_strlen($uploads_folder)+1);
                    $pos = $pos0 + $uploads_len;
                } else
                    $pos++;
            }
        }
        return $str;
    }
}

// Replace site url to current site url
if (!function_exists('grace_church_replace_site_url')) {
    function grace_church_replace_site_url($str, $old_url) {
        static $site_url = '', $site_len = 0;
        if (is_array($str) && count($str) > 0) {
            foreach ($str as $k=>$v) {
                $str[$k] = grace_church_replace_site_url($v, $old_url);
            }
        } else if (is_string($str)) {
            if (empty($site_url)) {
                $site_url = get_site_url();
                $site_len = grace_church_strlen($site_url);
                if (grace_church_substr($site_url, -1)=='/') {
                    $site_len--;
                    $site_url = grace_church_substr($site_url, 0, $site_len);
                }
            }
            if (grace_church_substr($old_url, -1)=='/') $old_url = grace_church_substr($old_url, 0, grace_church_strlen($old_url)-1);
            $break = '\'" ';
            $pos = 0;
            while (($pos = grace_church_strpos($str, $old_url, $pos))!==false) {
                $str = grace_church_unserialize($str);
                if (is_array($str) && count($str) > 0) {
                    foreach ($str as $k=>$v) {
                        $str[$k] = grace_church_replace_site_url($v, $old_url);
                    }
                    $str = serialize($str);
                    break;
                } else {
                    $pos0 = $pos;
                    $chg = true;
                    while ($pos0 >= 0) {
                        if (grace_church_strpos($break, grace_church_substr($str, $pos0, 1))!==false) {
                            $chg = false;
                            break;
                        }
                        if (grace_church_substr($str, $pos0, 5)=='http:' || grace_church_substr($str, $pos0, 6)=='https:')
                            break;
                        $pos0--;
                    }
                    if ($chg && $pos0>=0) {
                        $str = ($pos0 > 0 ? grace_church_substr($str, 0, $pos0) : '') . ($site_url) . grace_church_substr($str, $pos+grace_church_strlen($old_url));
                        $pos = $pos0 + $site_len;
                    } else
                        $pos++;
                }
            }
        }
        return $str;
    }
}

// Get domain part from URL
if (!function_exists('grace_church_get_domain_from_url')) {
    function grace_church_get_domain_from_url($url) {
        if (($pos=strpos($url, '://'))!==false) $url = substr($url, $pos+3);
        if (($pos=strpos($url, '/'))!==false) $url = substr($url, 0, $pos);
        return $url;
    }
}

// Return file extension from full name/path
if (!function_exists('grace_church_get_file_ext')) {
    function grace_church_get_file_ext($file) {
        $parts = pathinfo($file);
        return $parts['extension'];
    }
}


/* Check if file/folder present in the child theme and return path (url) to it.
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */

// Detect file location with next algorithm:
// 1) check in the child theme folder
// 2) check in the framework folder in the child theme folder
// 3) check in the main theme folder
// 4) check in the framework folder in the main theme folder
if (!function_exists('themerex_get_file_dir')) {
    function themerex_get_file_dir($file, $return_url=false) {
        if ($file[0]=='/') $file = themerex_substr($file, 1);
        $theme_dir = get_template_directory();
        $theme_url = get_template_directory_uri();
        $child_dir = get_stylesheet_directory();
        $child_url = get_stylesheet_directory_uri();
        $dir = '';
        if (file_exists(($child_dir).'/'.($file)))
            $dir = ($return_url ? $child_url : $child_dir).'/'.($file);
        else if (file_exists(($child_dir).'/'.(THEMEREX_FW_DIR).'/'.($file)))
            $dir = ($return_url ? $child_url : $child_dir).'/'.(THEMEREX_FW_DIR).'/'.($file);
        else if (file_exists(($theme_dir).'/'.($file)))
            $dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
        else if (file_exists(($theme_dir).'/'.(THEMEREX_FW_DIR).'/'.($file)))
            $dir = ($return_url ? $theme_url : $theme_dir).'/'.(THEMEREX_FW_DIR).'/'.($file);
        return $dir;
    }
}

// Return list files in folder
if (!function_exists('grace_church_get_list_files')) {
    function grace_church_get_list_files($folder, $ext='', $only_names=false, $return_url = true) {
        $dir = grace_church_get_folder_dir($folder);
        $url = grace_church_get_folder_url($folder);
        $list = array();
        if ( is_dir($dir) ) {
            $files = glob($dir . '/*.' . (!empty($ext) ? $ext : '*'));
            foreach ($files as $file) {
                $fname = basename($file);
                $key = grace_church_substr($fname, 0, grace_church_strrpos($fname, '.'));
                if (grace_church_substr($key, -4)=='.min') $key = grace_church_substr($key, 0, grace_church_strrpos($key, '.'));
                $list[$key] = $only_names
                    ? grace_church_strtoproper(str_replace('_', ' ', $key))
                    : ($return_url
                        ? $url . '/' . $fname
                        : $file
                    );
            }
        }
        return $list;
    }
}

/* Check if file/folder present in the child theme and return path (url) to it. 
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */

// Detect file location with next algorithm:
// 1) check in the skin folder in the child theme folder (optional, if $from_skin==true)
// 2) check in the child theme folder
// 3) check in the framework folder in the child theme folder
// 4) check in the skin folder in the main theme folder (optional, if $from_skin==true)
// 5) check in the main theme folder
// 6) check in the framework folder in the main theme folder
if (!function_exists('grace_church_get_file_dir')) {
	function grace_church_get_file_dir($file, $return_url=false, $from_skin=true) {
		static $skin_dir = '';
		if ($file[0]=='/') $file = grace_church_substr($file, 1);
		if ($from_skin && empty($skin_dir) && function_exists('grace_church_get_custom_option')) {
			$skin_dir = grace_church_esc(grace_church_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/' . ($skin_dir);
		}
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if ($from_skin && !empty($skin_dir) && file_exists(($child_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($skin_dir).'/'.($file);
		else if (file_exists(($child_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($file);
		else if (file_exists(($child_dir).(GRACE_CHURCH_FW_DIR).($file)))
			$dir = ($return_url ? $child_url : $child_dir).(GRACE_CHURCH_FW_DIR).($file);
		else if ($from_skin && !empty($skin_dir) && file_exists(($theme_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($skin_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
		else if (file_exists(($theme_dir).(GRACE_CHURCH_FW_DIR).($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).(GRACE_CHURCH_FW_DIR).($file);
		return $dir;
	}
}

if (!function_exists('grace_church_get_file_url')) {
	function grace_church_get_file_url($file) {
		return grace_church_get_file_dir($file, true);
	}
}

// Detect file location in the skin/theme/framework folders
if (!function_exists('grace_church_get_skin_file_dir')) {
	function grace_church_get_skin_file_dir($file) {
		return grace_church_get_skin_file_dir($file, false, true);
	}
}

if (!function_exists('grace_church_get_skin_file_url')) {
	function grace_church_get_skin_file_url($file) {
		return grace_church_get_skin_file_dir($file, true, true);
	}
}

// Detect folder location with same algorithm as file (see above)
if (!function_exists('grace_church_get_folder_dir')) {
	function grace_church_get_folder_dir($folder, $return_url=false, $from_skin=false) {
		static $skin_dir = '';
		if ($folder[0]=='/') $folder = grace_church_substr($folder, 1);
		if ($from_skin && empty($skin_dir) && function_exists('grace_church_get_custom_option')) {
			$skin_dir = grace_church_esc(grace_church_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/'.($skin_dir);
		}
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (!empty($skin_dir) && file_exists(($child_dir).'/'.($skin_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($skin_dir).'/'.($folder);
		else if (is_dir(($child_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($folder);
		else if (is_dir(($child_dir).(GRACE_CHURCH_FW_DIR).($folder)))
			$dir = ($return_url ? $child_url : $child_dir).(GRACE_CHURCH_FW_DIR).($folder);
		else if (!empty($skin_dir) && file_exists(($theme_dir).'/'.($skin_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($skin_dir).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($folder);
		else if (file_exists(($theme_dir).(GRACE_CHURCH_FW_DIR).($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).(GRACE_CHURCH_FW_DIR).($folder);
		return $dir;
	}
}

if (!function_exists('grace_church_get_folder_url')) {
	function grace_church_get_folder_url($folder) {
		return grace_church_get_folder_dir($folder, true);
	}
}

// Detect skin version of the social icon (if exists), else return it from template images directory
if (!function_exists('grace_church_get_socials_dir')) {
	function grace_church_get_socials_dir($soc, $return_url=false) {
		return grace_church_get_file_dir('images/socials/' . grace_church_esc($soc) . (grace_church_strpos($soc, '.')===false ? '.png' : ''), $return_url, true);
	}
}

if (!function_exists('grace_church_get_socials_url')) {
	function grace_church_get_socials_url($soc) {
		return grace_church_get_socials_dir($soc, true);
	}
}

// Detect theme version of the template (if exists), else return it from fw templates directory
if (!function_exists('grace_church_get_template_dir')) {
	function grace_church_get_template_dir($tpl) {
		return grace_church_get_file_dir('templates/' . grace_church_esc($tpl) . (grace_church_strpos($tpl, '.php')===false ? '.php' : ''));
	}
}
?>