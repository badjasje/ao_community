<?php

# handle better utf-8 and unicode encoding
if(function_exists('mb_internal_encoding')) { mb_internal_encoding('UTF-8'); }

# must have
@ini_set('pcre.backtrack_limit',5000000); 
@ini_set('pcre.recursion_limit',5000000);

# Consider fallback to PHP Minify [2017.12.17] from https://github.com/matthiasmullie/minify (must be defined on the outer scope)
$path = $plugindir . 'libs/matthiasmullie';
require_once $path . '/minify/src/Minify.php';
require_once $path . '/minify/src/CSS.php';
require_once $path . '/minify/src/JS.php';
require_once $path . '/minify/src/Exception.php';
require_once $path . '/minify/src/Exceptions/BasicException.php';
require_once $path . '/minify/src/Exceptions/FileImportException.php';
require_once $path . '/minify/src/Exceptions/IOException.php';
require_once $path . '/path-converter/src/ConverterInterface.php';
require_once $path . '/path-converter/src/Converter.php';
use MatthiasMullie\Minify;

# use HTML minification
require_once ($plugindir . 'libs/mrclay/HTML.php');

# get cache directories and urls
function fvm_cachepath() {

# custom directory
$fvm_change_cache_path = get_option('fastvelocity_min_change_cache_path');
$fvm_change_cache_base = get_option('fastvelocity_min_change_cache_base_url');
$upload = array();
if($fvm_change_cache_path !== false && $fvm_change_cache_base !== false && strlen($fvm_change_cache_path) > 1) {
	$upload['basedir'] = trim($fvm_change_cache_path);
	$upload['baseurl'] = trim($fvm_change_cache_base);
} else {
	$upload = wp_upload_dir(); # default 
}

# create
$cachebase = rtrim($upload['basedir'], '/').'/fvm';
$cachedir =  rtrim($upload['basedir'], '/').'/fvm/out';
$tmpdir = rtrim($upload['basedir'], '/').'/fvm/tmp';
$cachedirurl = rtrim($upload['baseurl'], '/').'/fvm/out';
$headerdir = rtrim($upload['basedir'], '/').'/fvm/header';
if(!is_dir($cachebase)) { mkdir($cachebase, 0755, true); }
if(!is_dir($cachedir)) { mkdir($cachedir, 0755, true); }
if(!is_dir($tmpdir)) { mkdir($tmpdir, 0755, true); }
if(!is_dir($headerdir)) { mkdir($headerdir, 0755, true); }

# return
return array('cachebase'=>$cachebase,'tmpdir'=>$tmpdir, 'cachedir'=>$cachedir, 'cachedirurl'=>$cachedirurl, 'headerdir'=>$headerdir);
}


# run during activation
function fastvelocity_plugin_activate() {
	
	# default options to enable
	$options_enable_default = array('fastvelocity_min_fvm_fix_editor', 'fastvelocity_min_remove_print_mediatypes',  'fastvelocity_fvm_clean_header_one', 'fastvelocity_gfonts_method');
	foreach($options_enable_default as $option) {
		update_option($option, 1, 'yes');
	}
	
	# increment time
	fvm_cache_increment();
	
	# default blacklist
	$exc = array('/html5shiv.js', '/html5shiv-printshiv.min.js', '/excanvas.js', '/avada-ie9.js', '/respond.js', '/respond.min.js', '/selectivizr.js', '/Avada/assets/css/ie.css', '/html5.js', '/IE9.js', '/fusion-ie9.js', '/vc_lte_ie9.min.css', '/old-ie.css', '/ie.css', '/vc-ie8.min.css', '/mailchimp-for-wp/assets/js/third-party/placeholders.min.js', '/assets/js/plugins/wp-enqueue/min/webfontloader.js', '/a.optnmstr.com/app/js/api.min.js', '/pixelyoursite/js/public.js');
	update_option('fastvelocity_min_blacklist', implode(PHP_EOL, $exc)); 

	# default ignore list
	$exc = array('/Avada/assets/js/main.min.js', '/woocommerce-product-search/js/product-search.js', '/includes/builder/scripts/frontend-builder-scripts.js', '/assets/js/jquery.themepunch.tools.min.js', '/js/TweenMax.min.js', '/jupiter/assets/js/min/full-scripts', '/wp-content/themes/Divi/core/admin/js/react-dom.production.min.js');
	update_option('fastvelocity_min_ignorelist', implode(PHP_EOL, $exc)); 
	
}

# run during deactivation
function fastvelocity_plugin_deactivate() {
	global $wpdb;
	
	# delete all fvm options
	$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'fastvelocity_%' OR option_name LIKE 'fvm-%'" );
	if(is_array($plugin_options) && count($plugin_options) > 0) {
		foreach( $plugin_options as $option ) { delete_option( $option->option_name ); }
	}
	
	# purge cache
	fvm_purge_all();
}



# detect external or internal scripts
function fvm_is_local_domain($src) {
$locations = array(home_url(), site_url(), network_home_url(), network_site_url());

	# cdn support
	$fvm_cdn_url = get_option('fastvelocity_min_fvm_cdn_url');
	$defer_for_pagespeed = get_option('fastvelocity_min_defer_for_pagespeed');
	$fvm_cdn_force = get_option('fastvelocity_min_fvm_cdn_force');
	
	# excluded from cdn because of https://www.chromestatus.com/feature/5718547946799104 (we use document.write to preserve render blocking)
	if(!empty($fvm_cdn_url) && ($defer_for_pagespeed != true || $fvm_cdn_force != false) ) {
		array_push($locations, $fvm_cdn_url);
	}
	
	# cleanup locations
	$locations = array_filter(array_unique($locations));

	# debug
	$debug = array('src'=>$src, 'fvm_cdn_url'=>$fvm_cdn_url, 'defer_for_pagespeed'=>$defer_for_pagespeed, 'fvm_cdn_force'=>$fvm_cdn_force, 'locations'=>$locations);
	
	
	# external or not?
	$ret = false;
	foreach ($locations as $l) { 
		$l = trim(trim(str_ireplace(array('http://', 'https://', 'www.'), '', trim($l)), '/')); 
		if (stripos($src, $l) !== false && $ret === false) { $ret = true; }
	}

# response
return $ret;
}


# functions, get hurl info
function fastvelocity_min_get_hurl($src, $wp_domain, $wp_home) {
	
# preserve empty source handles
$hurl = trim($src); if(empty($hurl)) { return $hurl; }      

# some fixes
$hurl = str_ireplace(array('&#038;', '&amp;'), '&', $hurl);

$default_protocol = get_option('fastvelocity_min_default_protocol', 'dynamic');
if($default_protocol == 'dynamic' || empty($default_protocol)) { 
if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) { $default_protocol = 'https://'; } else { $default_protocol = 'http://'; }
} else { 
$default_protocol = $default_protocol.'://'; 
}

#make sure wp_home doesn't have a forward slash
$wp_home = rtrim($wp_home, '/');

# apply some filters
if (substr($hurl, 0, 2) === "//") { $hurl = 'http://'.ltrim($hurl, "/"); }  # protocol only
if (substr($hurl, 0, 4) === "http" && stripos($hurl, $wp_domain) === false) { return $hurl; } # return if external domain
if (substr($hurl, 0, 4) !== "http" && stripos($hurl, $wp_domain) !== false) { $hurl = $wp_home.'/'.ltrim($hurl, "/"); } # protocol + home

# prevent double forward slashes in the middle
$hurl = str_ireplace('###', '://', str_ireplace('//', '/', str_ireplace('://', '###', $hurl)));

# consider different wp-content directory
$proceed = 0; if(!empty($wp_home)) { 
	$alt_wp_content = basename($wp_home); 
	if(substr($hurl, 0, strlen($alt_wp_content)) === $alt_wp_content) { $proceed = 1; } 
}

# protocol + home for relative paths
if (substr($hurl, 0, 12) === "/wp-includes" || substr($hurl, 0, 9) === "/wp-admin" || substr($hurl, 0, 11) === "/wp-content" || $proceed == 1) { 
$hurl = $wp_home.'/'.ltrim($hurl, "/"); }

# make sure there is a protocol prefix as required
$hurl = $default_protocol.str_ireplace(array('http://', 'https://'), '', $hurl); # enforce protocol

# no query strings
if (stripos($hurl, '.js?v') !== false) { $hurl = stristr($hurl, '.js?v', true).'.js'; } # no query strings
if (stripos($hurl, '.css?v') !== false) { $hurl = stristr($hurl, '.css?v', true).'.css'; } # no query strings

# make sure there is a protocol prefix as required
$hurl = fvm_compat_urls($hurl); # enforce protocol

return $hurl;	
}


# check if it's an internal url or not
function fvm_internal_url($hurl, $wp_home, $noxtra=NULL) {
if (substr($hurl, 0, strlen($wp_home)) === $wp_home) { return true; }
if (stripos($hurl, $wp_home) !== false) { return true; }
if (isset($_SERVER['HTTP_HOST']) && stripos($hurl, preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'])) !== false) { return true; }
if (isset($_SERVER['SERVER_NAME']) && stripos($hurl, preg_replace('/:\d+$/', '', $_SERVER['SERVER_NAME'])) !== false) { return true; }
if (isset($_SERVER['SERVER_ADDR']) && stripos($hurl, preg_replace('/:\d+$/', '', $_SERVER['SERVER_ADDR'])) !== false) { return true; }

# allow specific external urls to be merged
if($noxtra === NULL) {
$merge_allowed_urls = array_map('trim', explode(PHP_EOL, get_option('fastvelocity_min_merge_allowed_urls', '')));
if(is_array($merge_allowed_urls) && strlen(implode($merge_allowed_urls)) > 0) {
	foreach ($merge_allowed_urls as $e) {
		if (stripos($hurl, $e) !== false && !empty($e)) { return true; }
	}
}
}

return false;
}


# case-insensitive in_array() wrapper
function fastvelocity_min_in_arrayi($hurl, $ignore){
	$hurl = str_ireplace(array('http://', 'https://'), '//', $hurl); # better compatibility
	$hurl = strtok(urldecode(rawurldecode($hurl)), '?'); # no query string, decode entities
	
	if (!empty($hurl) && is_array($ignore)) { 
		foreach ($ignore as $i) {
			$i = str_ireplace(array('http://', 'https://'), '//', $i); # better compatibility
			$i = strtok(urldecode(rawurldecode($i)), '?'); # no query string, decode entities
			$i = trim(trim(trim(rtrim($i, '/')), '*')); # wildcard char removal
			if (stripos($hurl, $i) !== false) { return true; } 
		} 
	}
	return false;
}


# better compatibility urls + fix bootstrap 4 svg images https://www.w3.org/TR/SVG/intro.html#NamespaceAndDTDIdentifiers
function fvm_compat_urls($code) {
	$default_protocol = get_option('fastvelocity_min_default_protocol', 'dynamic');
	if($default_protocol == 'dynamic' || empty($default_protocol)) { 
		if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) { $default_protocol = 'https://'; } else { $default_protocol = 'http://'; }
	} else { 
		$default_protocol = $default_protocol.'://'; 
	}
	$code = str_ireplace(array('http://', 'https://'), $default_protocol, $code);
	$code = str_ireplace($default_protocol.'www.w3.org/2000/svg', 'http://www.w3.org/2000/svg', $code);
	return $code;
}


# minify css string with PHP Minify
function fastvelocity_min_minify_css_string($css) {
$minifier = new Minify\CSS($css);
$minifier->setMaxImportSize(15); # [css only] embed assets up to 15 Kb (default 5Kb) - processes gif, png, jpg, jpeg, svg & woff
$min = $minifier->minify();
if($min !== false) { return fvm_compat_urls($min); }
return fvm_compat_urls($css);
}


# find if we are running windows
function fvm_server_is_windows() {
	# PHP 7.2.0+
	if(defined('PHP_OS_FAMILY')) {
		if(strtolower(PHP_OS_FAMILY) == 'windows') { return true; }
	}
	if(function_exists('php_uname')) {
		$os = @php_uname('s');
		if (stripos($os, 'Windows') !== false) { 
			return true; 
		}
	}
	return false;
}



# minify js on demand (one file at one time, for compatibility)
function fastvelocity_min_get_js($url, $js, $disable_js_minification) {
	global $fvm_debug;

# exclude minification on already minified files + jquery (because minification might break those)
$excl = array('jquery.js', '.min.js', '-min.js', '/uploads/fusion-scripts/', '/min/', '.packed.js');
foreach($excl as $e) { if (stripos(basename($url), $e) !== false) { $disable_js_minification = true; break; } }

# remove BOM
$js = fastvelocity_min_remove_utf8_bom($js); 

# minify JS
if(!$disable_js_minification) { 
	$js = fastvelocity_min_minify_js_string($js); 
} else {
	$js = fvm_compat_urls($js); 
}

# needed when merging js files
$js = trim($js);
if(substr($js, -1) != ';'){ $js = $js.';'; }
if($fvm_debug == true) { $js = '/* info: ' . $url . ' */' . PHP_EOL . $js; }

# return html
return $js . PHP_EOL;
}


# minify JS string with PHP Minify or YUI Compressors
function fastvelocity_min_minify_js_string($js) {
global $tmpdir, $plugindir;

# PHP Minify [2016.08.01] from https://github.com/matthiasmullie/minify
$minifier = new Minify\JS($js);
$min = $minifier->minify();
if($min !== false && (strlen(trim($js)) == strlen(trim($min)) || strlen(trim($min)) > 0)) { return fvm_compat_urls($min); }

# if we are here, something went  wrong and minification didn't work
$js = "\n/*! Fast Velocity Minify: Minification of the following section has failed, so it has been merged instead. */\n".$js;
return fvm_compat_urls($js);
}

# functions, minify html
function fastvelocity_min_minify_html($html) {
return fastvelocity_min_Minify_HTML::minify($html);
}

# functions to minify HTML
function fastvelocity_min_html_compression_finish($html) { return fastvelocity_min_minify_html($html); }
function fastvelocity_min_html_compression_start() {
	if (fastvelocity_exclude_contents() == true) { return; }
	ob_start('fastvelocity_min_html_compression_finish');
}


# remove all cache files
function fastvelocity_rrmdir($path) {
	
	# remove cache files, set last update and clean legacy transients
	if(!get_option('fastvelocity_min_preserve_oldcache')) {
		
		# purge
		clearstatcache();
		if(is_dir($path)) {
			$i = new DirectoryIterator($path);
			foreach($i as $f){
				if($f->isFile()){ unlink($f->getRealPath());
				} else if(!$f->isDot() && $f->isDir()){
					fastvelocity_rrmdir($f->getRealPath());
					rmdir($f->getRealPath());
				}
			}
		}
	}
}




# return size in human format
function fastvelocity_format_filesize($bytes, $decimals = 2) {
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
    for ($i = 0; ($bytes / 1024) > 0.9; $i++, $bytes /= 1024) {}
    return sprintf( "%1.{$decimals}f %s", round( $bytes, $decimals ), $units[$i] );
}


# get cache size and count
function fastvelocity_get_cachestats() {
	clearstatcache();
	$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(fvm_cachepath()['cachebase'], FilesystemIterator::SKIP_DOTS));
	$size = 0; 
	foreach ($dir as $file) { 
		$size += $file->getSize(); 
	}
	return fastvelocity_format_filesize($size);
}




# minify css on demand (one file at one time, for compatibility)
function fastvelocity_min_get_css($url, $css, $disable_css_minification) {
global $wp_domain, $fvm_debug;

# remove BOM
$css = fastvelocity_min_remove_utf8_bom($css); 

# fix url paths
if(!empty($url)) { 
	$css = preg_replace("/url\(\s*['\"]?(?!data:)(?!http)(?![\/'\"])(.+?)['\"]?\s*\)/ui", "url(".dirname($url)."/$1)", $css); 
} 

# remove query strings from fonts (for better seo, but add a small cache buster based on most recent updates)
$ctime = get_option('fvm-last-cache-update', '0'); # last update or zero
$css = preg_replace('/(.eot|.woff2|.woff|.ttf)+[?+](.+?)(\)|\'|\")/ui', "$1"."#".$ctime."$3", $css); # fonts cache buster

# minify CSS
if(!$disable_css_minification) { 
	$css = fastvelocity_min_minify_css_string($css); 
} else {
	$css = fvm_compat_urls($css); 
}

# cdn urls
$fvm_cdn_url = get_option('fastvelocity_min_fvm_cdn_url');
if(!empty($fvm_cdn_url)) {
	$fvm_cdn_url = trim(trim(str_ireplace(array('http://', 'https://'), '', trim($fvm_cdn_url, '/'))), '/');
	$css = str_ireplace($wp_domain, $fvm_cdn_url, $css);
}

# add css comment
$css = trim($css);
if($fvm_debug == true) { $css = '/* info: ' . $url . ' */' . PHP_EOL . trim($css); }

# return html
return $css;
}



# get remote urls with curl
function fvm_file_get_contents_curl($url, $uagent=NULL) {
    $ch = curl_init();
	if(isset($uagent) && !empty($uagent)) { curl_setopt($ch,CURLOPT_USERAGENT, $uagent); }
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 10); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}



# download and cache css and js files
function fvm_download_and_minify($hurl, $inline, $disable_minification, $type, $handle){
global $cachedir, $cachedirurl, $wp_domain, $wp_home, $wp_home_path, $fvm_debug;

# must have
if(is_null($hurl) || empty($hurl)) { return false; }
if(!in_array($type, array('js', 'css'))) { return false; }

# defaults
if($disable_minification != true) { $disable_minification = false; }
if(is_null($inline) || empty($inline)) { $inline = ''; }
$printhandle = ''; if(is_null($handle) || empty($handle)) { $handle = ''; } else { $printhandle = "[$handle]"; }

# debug request
$dreq = array('hurl'=>$hurl, 'inline'=>$inline, 'disable_minification'=>$disable_minification, 'type'=>$type, 'handle'=>$handle);

# filters and defaults
$printurl = str_ireplace(array(site_url(), home_url(), 'http:', 'https:'), '', $hurl);

	# linux servers
	if(fvm_server_is_windows() === false) {
	if (stripos($hurl, $wp_domain) !== false) {
		# default
		$f = str_ireplace(rtrim($wp_home, '/'), rtrim($wp_home_path, '/'), $hurl);
		clearstatcache();
		if (file_exists($f)) { 
			if($type == 'js') { 
				$code = fastvelocity_min_get_js($hurl, file_get_contents($f), $disable_minification); 
			} else { 
				$code = fastvelocity_min_get_css($hurl, file_get_contents($f).$inline, $disable_minification); 
			}
			
			# log, save and return
			$log = $printurl;
			if($fvm_debug == true) { $log.= " --- Debug: $printhandle was opened from $f ---"; }
			$log.= PHP_EOL;
			$return = array('request'=>$dreq, 'log'=>$log, 'code'=>$code, 'status'=>true);
			return json_encode($return);
		}
		
		# failover when home_url != site_url
		$nhurl = str_ireplace(site_url(), home_url(), $hurl);
		$f = str_ireplace(rtrim($wp_home, '/'), rtrim($wp_home_path, '/'), $nhurl);
		clearstatcache();
		if (file_exists($f)) { 
			if($type == 'js') { 
				$code = fastvelocity_min_get_js($hurl, file_get_contents($f), $disable_minification); 
			} else { 
				$code = fastvelocity_min_get_css($hurl, file_get_contents($f).$inline, $disable_minification); 
			}
			
			# log, save and return
			$log = $printurl;
			if($fvm_debug == true) { $log.= " --- Debug: $printhandle was opened from $f ---"; }
			$log.= PHP_EOL;
			$return = array('request'=>$dreq, 'log'=>$log, 'code'=>$code, 'status'=>true);
			return json_encode($return);
		}
	}
	}


	# else, fallback to remote urls (or windows)
	$code = fastvelocity_download($hurl);
	if($code !== false) { 
		if($type == 'js') { 
			$code = fastvelocity_min_get_js($hurl, $code, $disable_minification); 
		} else { 
			$code = fastvelocity_min_get_css($hurl, $code.$inline, $disable_minification); 
		}
		
		# log, save and return
		$log = $printurl;
		if($fvm_debug == true) { $log.= " --- Debug: $printhandle was fetched from $hurl ---"; }
		$log.= PHP_EOL;
		$return = array('request'=>$dreq, 'log'=>$log, 'code'=>$code, 'status'=>true);
		return json_encode($return);
	}


	# fallback when home_url != site_url
	if(stripos($hurl, $wp_domain) !== false && home_url() != site_url()) {
		$nhurl = str_ireplace(site_url(), home_url(), $hurl);
		$code = fastvelocity_download($nhurl);
		if($code !== false) { 
			if($type == 'js') { 
				$code = fastvelocity_min_get_js($hurl, $code, $disable_minification); 
			} else { 
				$code = fastvelocity_min_get_css($hurl, $code.$inline, $disable_minification); 
			}
			
			# log, save and return
			$log = $printurl;
			if($fvm_debug == true) { $log.= " --- Debug: $printhandle was fetched from $hurl ---"; }
			$log.= PHP_EOL;
			$return = array('request'=>$dreq, 'log'=>$log, 'code'=>$code, 'status'=>true);
			return json_encode($return);
		}
	}


	# if remote urls failed... try to open locally again, regardless of OS in use
	if (stripos($hurl, $wp_domain) !== false) { 
		# default
		$f = str_ireplace(rtrim($wp_home, '/'), rtrim($wp_home_path, '/'), $hurl);
		clearstatcache();
		if (file_exists($f)) { 
			if($type == 'js') {
				$code = fastvelocity_min_get_js($hurl, file_get_contents($f), $disable_minification); 
			} else { 
				$code = fastvelocity_min_get_css($hurl, file_get_contents($f).$inline, $disable_minification); 
			}
			
			# log, save and return
			$log = $printurl;
			if($fvm_debug == true) { $log.= " --- Debug: $printhandle was opened from $f ---"; }
			$log.= PHP_EOL;
			$return = array('request'=>$dreq, 'log'=>$log, 'code'=>$code, 'status'=>true);
			return json_encode($return);
		}
		
		# failover when home_url != site_url
		$nhurl = str_ireplace(site_url(), home_url(), $hurl);
		$f = str_ireplace(rtrim($wp_home, '/'), rtrim($wp_home_path, '/'), $nhurl);
		clearstatcache();
		if (file_exists($f)) { 
			if($type == 'js') { 
				$code = fastvelocity_min_get_js($hurl, file_get_contents($f), $disable_minification); 
			} else { 
				$code = fastvelocity_min_get_css($hurl, file_get_contents($f).$inline, $disable_minification); 
			}
			
			# log, save and return
			$log = $printurl;
			if($fvm_debug == true) { $log.= " --- Debug: $printhandle was opened from $f ---"; }
			$log.= PHP_EOL;
			$return = array('request'=>$dreq, 'log'=>$log, 'code'=>$code, 'status'=>true);
			return json_encode($return);
		}
	}

	
	# else fail
	$log = $printurl;
	if($fvm_debug == true) { $log.= " --- Debug: $printhandle failed. Tried wp_remote_get, curl and local file_get_contents. ---"; }
	$return = array('request'=>$dreq, 'log'=>$log, 'code'=>'', 'status'=>false);
	return json_encode($return);
}



# Concatenate Google Fonts tags (http://fonts.googleapis.com/css?...)
function fastvelocity_min_concatenate_google_fonts($array) {

# extract unique font families
$families = array(); foreach ($array as $font) {

# get fonts name, type and subset, remove wp query strings
$font = explode('family=', htmlspecialchars_decode(rawurldecode(urldecode($font))));
$a = explode('&v', end($font)); $font = trim(trim(trim(current($a)), ','));

# reprocess if fonts are already concatenated in this url
if(stristr($font, '|') !== FALSE) { 
	$multiple = explode('|', $font); if (count($multiple) > 0) { foreach ($multiple as $f) { $families[] = trim($f); } }
} else { $families[] = $font; }
}

# process types, subsets, merge, etc
$fonts = array(); 
foreach ($families as $font) {
		
# if no type or subset
if(stristr($font, ':') === FALSE) { 
	$fonts[] = array('name'=>$font, 'type'=>'', 'sub'=>''); 
} else {

	# get type and subset
	$name = stristr($font, ':', true);       # font name, before :
	$ftype = trim(stristr($font, ':'), ':'); # second part of the string, after :

	# get font types and subset
	if(stristr($ftype, '&subset=') === FALSE) { 
		$fonts[] = array('name'=>$name, 'type'=>$ftype, 'sub'=>''); 
	} else { 
		$newftype = stristr($ftype, '&', true);        # font type, before &
		$subset = trim(str_ireplace('&subset=', '', stristr($ftype, '&')));     # second part of the string, after &
		$fonts[] = array('name'=>$name, 'type'=>$newftype, 'sub'=>$subset); 
	}

}
}

# make sure we have unique font names, types and subsets
$ufonts = array(); foreach ($fonts as $f) { $ufonts[$f['name']] = $f['name']; }                              # unique font names
$usubsets = array(); foreach ($fonts as $f) { if(!empty($f['sub'])) { $usubsets[$f['sub']] = $f['sub']; } }  # unique subsets

# prepare
$fonts_and_types = $ufonts;

# get unique types and subsets for each unique font name
foreach ($ufonts as $uf) {
	
	# types
	$utypes = array(); 
	foreach ($fonts as $f) {
		if($f['name'] == $uf && !empty($f['type'])) { $utypes = array_merge($utypes, explode(',', $f['type'])); }
	}
	
	# filter types
	$utypes = array_unique($utypes);
    sort($utypes);
	$ntype = ''; if(count($utypes) > 0) { $ntype = ':'.implode(',', $utypes); } # types to append to the font name
	
	# generate font url queries
	$fonts_and_types[$uf] = str_ireplace(' ', '+', $uf).$ntype;
}

# concat fonts, generate unique google fonts url
if(count($fonts_and_types) > 0) {
	$msubsets = ''; if(count($usubsets) > 0 && implode(',', $usubsets) != 'latin') { $msubsets = "&subset=".implode(',', $usubsets); } # merge subsets
	return trim('https://fonts.googleapis.com/css?family='.implode('|', $fonts_and_types).$msubsets); # process
}

return false;
}


# remove emoji support
function fastvelocity_min_disable_wp_emojicons() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

# remove from tinymce
function fastvelocity_disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}



# escape double quotes
function fastvelocity_escape_double($string) {
	return str_ireplace(array('"', '\\"', '\\\"'), '\"', $string);
}


# remove UTF8 BOM
function fastvelocity_min_remove_utf8_bom($text) {
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/ui", '', $text);
    return $text;
}


# Remove query string from static css files
function fastvelocity_remove_cssjs_ver( $src ) {
 if(stripos($src, '?ver=')) { $src = remove_query_arg('ver', $src); }
 return $src;
}


# rewrite cache files to http, https or dynamic
function fvm_get_protocol($url) {
	global $wp_domain;
	$url = ltrim(str_ireplace(array('http://', 'https://'), '', $url), '/'); # better compatibility

	# cdn support
	$fvm_cdn_url = get_option('fastvelocity_min_fvm_cdn_url');
	$fvm_cdn_url = trim(trim(str_ireplace(array('http://', 'https://'), '', trim($fvm_cdn_url, '/'))), '/');
	
	# process cdn rewrite
	if(!empty($fvm_cdn_url) && fvm_is_local_domain($url) !== false) {
		
		# for js files, we need to consider thew defer for insights option
		if(substr($url, -3) == '.js') {
			
			$defer_for_pagespeed = get_option('fastvelocity_min_defer_for_pagespeed');
			$fvm_cdn_force = get_option('fastvelocity_min_fvm_cdn_force');
			
			if($defer_for_pagespeed != true || $fvm_cdn_force != false) {
				$url = str_ireplace($wp_domain, $fvm_cdn_url, $url);
			}
		
		} else {
			$url = str_ireplace($wp_domain, $fvm_cdn_url, $url);
		}
	}

	# enforce protocol if needed
	$default_protocol = get_option('fastvelocity_min_default_protocol', 'dynamic');
	if($default_protocol == 'dynamic' || empty($default_protocol)) { 
		if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) { $default_protocol = 'https://'; } else { $default_protocol = 'http://'; }
	} else { 
		$default_protocol = $default_protocol.'://'; 
	}
	
	# return
	return $default_protocol.$url;
}


# increment file names
function fvm_cache_increment() {
	update_option('fvm-last-cache-update', time());
}

# purge all caches
function fvm_purge_all() {

	# get cache directories and urls
	$cachepath = fvm_cachepath();
	$cachebase = $cachepath['cachebase'];

	# delete minification files and transients
	if(!is_dir($cachebase)) { 
		return false; 
	}
	
	fastvelocity_rrmdir($cachebase);
	fvm_cache_increment(); 	
	do_action('fvm_after_purge_all');
	return true;
}


# get transients on the disk
function fvm_get_transient($key) {
	$cachepath = fvm_cachepath();
	$tmpdir = $cachepath['tmpdir'];
	$f = $tmpdir.'/'.$key.'.transient';
	clearstatcache();
	if(file_exists($f)) {
		return file_get_contents($f);
	} else {
		return false;
	}
}

# set cache on disk
function fvm_set_transient($key, $code) {
	if(is_null($code) || empty($code)) { return false; }
	$cachepath = fvm_cachepath();
	$tmpdir = $cachepath['tmpdir'];
	$f = $tmpdir.'/'.$key.'.transient';
	file_put_contents($f, $code);
	return true;
}


# generate ascii slug
function fvm_safename($str, $noname=NULL) {
	$nstr = preg_replace("/[^a-zA-Z0-9]+/", "-", $str);
	$nstr = trim(trim($nstr, '-'));
	if(strlen($nstr) > 1) { 
		return $nstr; 
	}
	
	# return false if no empty name rewrite requested
	if($noname !== NULL) {
		return false;
	}
	
	# fallback
	return 'noname-'.hash('adler32', $str); 
}


# exclude processing from some pages / posts / contents
function fastvelocity_exclude_contents() {
	
	# exclude processing here
	if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX) || (function_exists('wp_doing_ajax') && wp_doing_ajax()) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('WP_BLOG_ADMIN') && WP_BLOG_ADMIN) || (defined('WP_NETWORK_ADMIN') && WP_NETWORK_ADMIN) || (defined('WP_INSTALLING') && WP_INSTALLING) || (defined('WP_IMPORTING') && WP_IMPORTING) || (defined('WP_REPAIRING') && WP_REPAIRING) || (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) || (defined('SHORTINIT') && SHORTINIT) || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') || 
	(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (isset($_SERVER['REQUEST_URI']) && (substr($_SERVER['REQUEST_URI'], -4) == '.txt' || substr($_SERVER['REQUEST_URI'], -4) == '.xml'))) {
		return true;
	}
	
	# customizer preview, visual composer
	$arr = array('customize_theme', 'preview_id', 'preview');
	foreach ($arr as $a) { if(isset($_GET[$a])) { return true; } }

	# Thrive plugins and other post_types
	$arr = array('tve_form_type', 'tve_lead_shortcode', 'tqb_splash');
	foreach ($arr as $a) { if(isset($_GET['post_type']) && $_GET['post_type'] == $a) { return true; } }
	
	# default
	return false;
}

# Know files that should always be ignored
function fastvelocity_default_ignore($ignore) {
if(is_array($ignore)) {
	
	# from the database
	$exc = array_map('trim', explode(PHP_EOL, get_option('fastvelocity_min_ignorelist', '')));
	
	# should we exclude jquery when defer is enabled?
	$exclude_defer_jquery = get_option('fastvelocity_min_exclude_defer_jquery');
	$enable_defer_js = get_option('fastvelocity_min_enable_defer_js');
	if($enable_defer_js == true && $exclude_defer_jquery == true) {
		$exc[] = '/jquery.js';
		$exc[] = '/jquery.min.js';
	}

	# make sure it's unique and not empty
	$uniq = array();
	foreach ($ignore as $i) { $k = hash('adler32', $i); if(!empty($i)) { $uniq[$k] = $i; } }
	foreach ($exc as $e) { $k = hash('adler32', $e); if(!empty($e)) { $uniq[$k] = $e; } }

	# merge and return
	return $uniq;
} else { return $ignore; }
}


# IE only files that should always be ignored, without incrementing our groups
function fastvelocity_ie_blacklist($url) {

	# from the database
	$exc = array_map('trim', explode(PHP_EOL, get_option('fastvelocity_min_blacklist', '')));
	
	# must have
	$exc[] = '/fvm/cache/';
	
	# is the url on our list and return
	$res = fastvelocity_min_in_arrayi($url, $exc);
	if($res == true) { return true; } else { return false; }
}


# download function with cache support and fallback
function fastvelocity_download($url) {
	
	# info (needed for google fonts woff files + hinted fonts) as well as to bypass some security filters
	$uagent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

	# fetch via wordpress functions
	$response = wp_remote_get($url, array('user-agent'=>$uagent, 'timeout' => 7, 'httpversion' => '1.1', 'sslverify'=>false)); 
	$res_code = wp_remote_retrieve_response_code($response);
	if($res_code == '200') { 			
		$data = wp_remote_retrieve_body($response);
		if(strlen($data) > 1) {
			return $data; 
		}
	}	
	
	# fallback, let's try curl if available
	if(function_exists('curl_version')) {
		$curl = fvm_file_get_contents_curl($url, $uagent);
		if(!empty($curl) && strlen($curl) > 1) {
			return $data;
		}
	}

	# fallback fail
	return false;
}


# Purge Godaddy Managed WordPress Hosting (Varnish)
# https://github.com/wp-media/wp-rocket/blob/master/inc/3rd-party/hosting/godaddy.php
function fastvelocity_godaddy_request( $method, $url = null ) {
	$url  = empty( $url ) ? home_url() : $url;
	$host = parse_url( $url, PHP_URL_HOST );
	$url  = set_url_scheme( str_replace( $host, WPaas\Plugin::vip(), $url ), 'http' );
	wp_cache_flush();
	update_option( 'gd_system_last_cache_flush', time() ); # purge apc
	wp_remote_request( esc_url_raw( $url ), array('method' => $method, 'blocking' => false, 'headers' => array('Host' => $host)) );
}


function fastvelocity_purge_others(){
	
# wordpress default cache
if (function_exists('wp_cache_flush')) {
	wp_cache_flush();
}
	
# Purge all W3 Total Cache
if (function_exists('w3tc_pgcache_flush')) {
	w3tc_pgcache_flush();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>W3 Total Cache</strong> have also been purged.</p></div>');
}

# Purge WP Super Cache
if (function_exists('wp_cache_clear_cache')) {
	wp_cache_clear_cache();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>WP Super Cache</strong> have also been purged.</p></div>');
}

# Purge WP Rocket
if (function_exists('rocket_clean_domain')) {
	rocket_clean_domain();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>WP Rocket</strong> have also been purged.</p></div>');
}

# Purge Wp Fastest Cache
if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
	$GLOBALS['wp_fastest_cache']->deleteCache();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>Wp Fastest Cache</strong> have also been purged.</p></div>');
}

# Purge Cachify
if (function_exists('cachify_flush_cache')) {
	cachify_flush_cache();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>Cachify</strong> have also been purged.</p></div>');
}

# Purge Comet Cache
if ( class_exists("comet_cache") ) {
	comet_cache::clear();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>Comet Cache</strong> have also been purged.</p></div>');
}

# Purge Zen Cache
if ( class_exists("zencache") ) {
	zencache::clear();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>Comet Cache</strong> have also been purged.</p></div>');
}

# Purge LiteSpeed Cache 
if (class_exists('LiteSpeed_Cache_Tags')) {
	LiteSpeed_Cache_Tags::add_purge_tag('*');
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>LiteSpeed Cache</strong> have also been purged.</p></div>');
}

# Purge SG Optimizer
if (function_exists('sg_cachepress_purge_cache')) {
	sg_cachepress_purge_cache();
	return __('<div class="notice notice-info is-dismissible"><p>All caches from <strong>SG Optimizer</strong> have also been purged.</p></div>');
}

# Purge Hyper Cache
if (class_exists( 'HyperCache' )) {
	do_action( 'autoptimize_action_cachepurged' );
	return __( '<div class="notice notice-info is-dismissible"><p>All caches from <strong>HyperCache</strong> have also been purged.</p></div>');
}

# Purge Godaddy Managed WordPress Hosting (Varnish + APC)
if (class_exists('WPaaS\Plugin')) {
	fastvelocity_godaddy_request('BAN');
	return __('<div class="notice notice-info is-dismissible"><p>A cache purge request has been sent to <strong>Go Daddy Varnish</strong></p></div><div class="notice notice-info is-dismissible"><p>Please note that it may not work 100% of the time, due to cache rate limiting by your host!</p></div>');
}

# purge cache enabler
if ( has_action('ce_clear_cache') ) {
    do_action('ce_clear_cache');
	return __( '<div class="notice notice-info is-dismissible"><p>All caches from <strong>Cache Enabler</strong> have also been purged.</p></div>');
}


# Purge WP Engine
if (class_exists("WpeCommon")) {
	if (method_exists('WpeCommon', 'purge_memcached')) { WpeCommon::purge_memcached(); }
	if (method_exists('WpeCommon', 'clear_maxcdn_cache')) { WpeCommon::clear_maxcdn_cache(); }
	if (method_exists('WpeCommon', 'purge_varnish_cache')) { WpeCommon::purge_varnish_cache(); }

	if (method_exists('WpeCommon', 'purge_memcached') || method_exists('WpeCommon', 'clear_maxcdn_cache') || method_exists('WpeCommon', 'purge_varnish_cache')) {
		return __('<div class="notice notice-info is-dismissible"><p>A cache purge request has been sent to <strong>WP Engine</strong></p></div><div class="notice notice-info is-dismissible"><p>Please note that it may not work 100% of the time, due to cache rate limiting by your host!</p></div>');
	}
}

}


