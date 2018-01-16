<?php
/*
Plugin Name: VigilanTor
Plugin URI: https://drew-phillips.com/
Description: Provides protections from Tor users visiting your site
Version: 1.3.2
Author: Drew Phillips
Author URI: https://drew-phillips.com
Text Domain: vigilantor
*/

/*  Copyright (C) 2017 Drew Phillips

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

defined('VIGILANTOR_VERSION') || define('VIGILANTOR_VERSION', '1.3.1');

if (class_exists('VigilanTorWP')) {
    register_activation_hook(__FILE__, array('VigilanTorWP', 'install'));

    if (version_compare($wp_version, '4.6') == -1) {
        function vigilantor_load_plugin_textdomain() {
            load_plugin_textdomain( 'vigilantor', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
        }
        add_action( 'plugins_loaded', 'vigilantor_load_plugin_textdomain' );
    }

    VigilanTorWP::run();
    return ;
}

class VigilanTorWP
{
    private $_wpdb;
    private $_menuSlug = 'vigilantor';
    private $_updateFrequencies;
    private $_exitList;
    private $_customBlockList;
    private $_realtimeEnabled = false;
    private $_useCustomBlockList = false;
    private $_blockedByCustomList = false;
    private static $_instance = null;

    const EXIT_LIST_UPDATE_URL = '//openinternet.io/tor/tor-ip-list.txt';

    private function __construct()
    {
        global $wpdb;

        $this->_wpdb = $wpdb;

        $this->_updateFrequencies = array(
            '_10m'  => array('interval' => 600,  'display' => __('Every 10 minutes')),
            '_20m'  => array('interval' => 1200, 'display' => __('Every 20 minutes')),
            '_30m'  => array('interval' => 1800, 'display' => __('Every half hour')),
            '_60m'  => array('interval' => HOUR_IN_SECONDS, 'display' => __('Every hour')),
            '_120m' => array('interval' => HOUR_IN_SECONDS * 2, 'display' => __('Every 2 hours')),
            '_360m' => array('interval' => HOUR_IN_SECONDS * 6, 'display' => __('Every 6 hours')),
        );

        add_action   ('wp', array(&$this, 'init'), 0, 1);
        add_action   ('admin_menu', array(&$this, 'adminMenu'));
        add_action   ('vitor_update_lists', array(&$this, 'updateExitList'));
        add_action   ('wp_ajax_vitor_clear_flag', array(&$this, 'clearFlagAction'));
        add_filter   ('widget_text', 'do_shortcode');
        add_filter   ('cron_schedules', array(&$this, '_addSchedules'));
        add_shortcode('tor_users', array(&$this, 'doTorUserShortcode'));


        if (true == get_option('vitor_enable_realtime', 0)) {
            $this->_realtimeEnabled = true;
        }

        if (true == get_option('vitor_custom_blocklist_enabled', 0)) {
            $this->_useCustomBlockList= true;
        }

        if (true == get_option('vitor_block_comments', 0) || true == get_option('vitor_block_trackbacks', 0)) {
            add_action('preprocess_comment', array(&$this, 'preProcessCommentAction'), 0, 1);

            if (true == get_option('vitor_hide_commentform', 0) && true == get_option('vitor_block_comments', 0)) {
                add_action('comment_form_before', array(&$this, 'blockCommentForm'));
            }
        }

        if (true == get_option('vitor_block_registration', 0)) {
            add_action('register_post', array(&$this, 'processRegistrationAction'), 0, 3);

            // buddypress integration
            if (function_exists('buddypress')) {
                add_action('bp_signup_validate', array(&$this, 'processBPRegistrationAction'), 0);
            }
        } else if (true == get_option('vitor_flag_registration', 0)) {
            global $wp_version;

            if (version_compare($wp_version, '4.4') >= 0) {
                add_action('register_new_user', array(&$this, 'postRegistrationAction'), 100, 1);
            } else {
                add_action('user_register', array(&$this, 'postRegistrationAction'), 100, 1);
            }

            if (function_exists('buddypress')) {
                add_action('bp_core_signup_user', array(&$this, 'postBPRegistrationAction'), 100, 5);
            }
        }

        if (true == get_option('vitor_block_login', 0)) {
            add_filter('authenticate', array(&$this, 'processLoginAction'), 10, 3);
            add_action('wp_authenticate', array(&$this, 'wpAuthCallback'), 1, 2);
        }
    }

    public function wpAuthCallback(&$username, &$password) {
        // get instance (PHP 5.3). PHP 5.4+ could reference $this here
        $vt = VigilanTorWP::run();
        if ($vt->isTorUser()) {
            $vt->_blockLogin    = true;
            $username = $password = null;
        }
    }

    public static function run()
    {
        if (self::$_instance) {
            return self::$_instance;
        } else {
            self::$_instance = new self();
            return self::$_instance;
        }
    }

    public function init(&$wp)
    {
        if (true == get_option('vitor_block_everything', 0)) {
            $this->blockWPAccess();
        }

        if (isset($_GET['_vitor_action']) && $_GET['_vitor_action'] == 'update') {
            $lastUpdate = get_option('vitor_exit_list_last_updated', 0);
            $frequency  = get_option('vitor_el_update_frequency', '_10m');

            $updated = 0;
            // only update if the list is older than the update interval
            if (time() - $lastUpdate > $this->_updateFrequencies[$frequency]['interval']) {
                $this->updateExitList();
                $updated = 1;
            }

            echo $updated;
            exit;
        }
    }

    public function blockWPAccess()
    {
        global $wp_query, $id;

        if (!$this->isTorUser()) {
            return ;
        }

        if ( (bool)get_option('vitor_use_captcha', false) === true ) {
            if (function_exists('siwp_captcha_html')) {
                if ($this->_checkCookie() === true) {
                    return ;
                }

                $captcha = $this->_getCaptchaHtml();
            }
        }

        update_site_option('vitor_stat_blockedviews', (int)get_site_option('vitor_stat_blockedviews') + 1);

        $block_page_id = (int)get_option('vitor_blocked_page', 0);

        if ($block_page_id == 0 || !($post = get_post($block_page_id))) {
            if ($this->_blockedByCustomList) {
                $message = get_option('vitor_custom_block_message', null);
                if (empty($message)) {
                    $message = __('Sorry, your IP address or network is blocked from accessing this site.', 'vigilantor');
                }
            } else {
                $message  = __('Sorry, you cannot access this website using Tor.', 'vigilantor');
            }

            if (isset($captcha)) {
                $message .= "<style type='text/css'>
                             input[type=text] {
                             margin-top: 5px;
                             display: block;
                             width: 50%;
                             font-size: 18px;
                             }
                             label {
                               font-size: 1.2em;
                               padding-bottom: 20px;
                             }
                             .error {
                               margin-top: 10px;
                               color: #f00;
                               font-weight: bold;
                             }
                             </style>";

                $message .= '<script type="text/javascript" src="' . siwp_get_plugin_url() . 'lib/securimage-wp.js"></script>';
                $message .= $captcha;
            }

            wp_die($message, '', 403);
        } else {
            header('HTTP/1.1 403 Forbidden');

            // some themes reference $id in page templates
            $id = $block_page_id;

            // remove Private: from title
            $post->post_status = 'publish';

            // hijack the wp_query to inject our block page
            $wp_query->current_post = -1;
            $wp_query->post_count   = 1;
            $wp_query->posts        = array($post);

            $content  = apply_filters('the_content', $post->post_content);

            if (isset($captcha)) $content .= $captcha;

            $post->post_content = $content;

            // render block page with template
            $template = get_page_template();
            include $template;
            exit;
        }
    }

    public function preProcessCommentAction($commentdata)
    {
        $isTrackback = (!empty($commentdata['comment_type']) && in_array($commentdata['comment_type'], array('pingback', 'trackback')));

        if (false == get_option('vitor_block_comments', 0) && false == $isTrackback) {
            // comments okay for Tor, trackbacks are not
            return $commentdata;
        }
        if (false == get_option('vitor_block_trackbacks', 0) && true == $isTrackback) {
            // trackbacks okay for Tor, comments are not
            return $commentdata;
        }

        // admin comment reply using ajax from admin panel
        if ( isset($_POST['_ajax_nonce-replyto-comment']) && check_ajax_referer('replyto-comment', '_ajax_nonce-replyto-comment')) {
            return $commentdata;
        }

        // admin comment from comment form
        if (is_user_logged_in() && current_user_can('administrator')) {
            return $commentdata;
        }

        // compatibility with tiled gallery carousel without jetpack
        if ( isset($_POST['action']) && $_POST['action'] == 'post_attachment_comment' && basename($_SERVER['REQUEST_URI']) == 'admin-ajax.php' && is_plugin_active('tiled-gallery-carousel-without-jetpack/tiled-gallery.php') ) {
            return $commentdata;
        }

        if ($this->isTorUser()) {
            if ($isTrackback) {
                update_site_option('vitor_stat_trackbacks', (int)get_site_option('vitor_stat_trackbacks') + 1);
                trackback_response(true, __('Sorry, trackbacks are not allowed from Tor IP addresses!', 'vigilantor'));
            } else {
                update_site_option('vitor_stat_comments', (int)get_site_option('vitor_stat_comments') + 1);
                wp_die(__('Error:') . ' ' . __('You appear to be commenting from a Tor IP address which is not allowed.', 'vigilantor'));
            }
        }

        return $commentdata;
    }

    public function blockCommentForm()
    {
        if ($this->isTorUser() || $this->findInCustomBlocklist($this->getClientIpAddress())) {
            ob_start();
            add_action('comment_form_after', array(&$this, 'endBlockCommentForm'));
        }
    }

    public function endBlockCommentForm()
    {
        ob_end_clean();
    }

    public function processRegistrationAction($username, $email, WP_Error $errors)
    {
        if ($this->isTorUser()) {
            update_site_option('vitor_stat_registration', (int)get_site_option('vitor_stat_registration') + 1);
            $errors->add('registerfail', __('Sorry, you are not allowed to register for this site while using Tor!', 'vigilantor'));
            return false;
        }

        return true;
    }

    public function processBPRegistrationAction()
    {
        global $bp;

        $error = '';
        if ($this->isTorUser()) {
            update_site_option('vitor_stat_registration', (int)get_site_option('vitor_stat_registration') + 1);
            $error = __('Sorry, you are not allowed to register for this site while using Tor!', 'vigilantor');
            $bp->signup->errors['vitor'] = $error; // have to set some error otherwise registration is allowed
            $GLOBALS['_vitor_bp_registration_error'] = $error;
            add_action('bp_before_account_details_fields', array(&$this, 'bpOutputRegistrationError'));
            return false;
        }

        return true;
    }

    public function bpOutputRegistrationError() {
        global $_vitor_bp_registration_error;

        echo "<div><div class='error'>{$_vitor_bp_registration_error}</div></div>";
    }

    public function postRegistrationAction($user_id)
    {
        if ($this->isTorUser()) {
            update_user_meta($user_id, 'vitor_flagged_registration', $this->getClientIpAddress());
        }
    }

    public function postBPRegistrationAction($user_id, $user_login, $user_password, $user_email, $usermeta)
    {
        if ($this->isTorUser()) {
            update_user_meta($user_id, 'vitor_flagged_registration', $this->getClientIpAddress());
        }
    }

    public function processLoginAction($user, $username, $password)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($this->_blockLogin) && $this->_blockLogin === true) {
            update_site_option('vitor_stat_login', (int)get_site_option('vitor_stat_login') + 1);
            $error = new WP_Error();
            $error->add('tor_login', __('<strong>ERROR</strong>: You cannot log in to this website using Tor.', 'vigilantor'));
            return $error;
        }
    }

    public function doTorUserShortcode($atts, $content = '')
    {
        if ($this->isTorUser()) {
            return $content;
        } else {
            return '';
        }
    }

    public function isTorUser()
    {
        static $called = false;

        if (current_user_can('administrator')) return false;

        $lastUpdate = get_option('vitor_exit_list_last_updated', 0);
        $frequency  = get_option('vitor_el_update_frequency', '_10m');

        if (time() - $lastUpdate > $this->_updateFrequencies[$frequency]['interval']) {
            // workaround for sites where wp-cron isn't working for one reason or another
            if (!$called) {
                // append script to run update in background
                // this prevents the update from slowing down the page load for the visitor
                // if wp-cron is working properly, the update will never need to be forced like this
                add_action('wp_footer', array(&$this, 'enqueueUpdateScript'));
            }
        }

        $called = true;
        $ip     = $this->getClientIpAddress();

        if ($this->_realtimeEnabled) {
            if ($this->doRealTimeLookup() === true) {
                return true;
            }
        }

        if ($this->findInExitList($ip)) {
            return true;
        }

        if ($this->_useCustomBlockList) {
            if ($this->findInCustomBlocklist($ip)) {
                $this->_blockedByCustomList = true;
                return true;
            }
        }

        return false;
    }

    public function doRealTimeLookup()
    {
        $cacheTime = 300;
        $cacheKey  = 'torip_' . md5($_SERVER['REMOTE_ADDR']);

        require_once dirname(__FILE__) . '/lib/TorDNSEL.php';

        if (false === ($ipResult = get_site_transient($cacheKey))) {
            try {
                $result = TorDNSEL::setTimeout(3)->IpPort(
                        $_SERVER['SERVER_ADDR'],
                        $_SERVER['SERVER_PORT'],
                        $_SERVER['REMOTE_ADDR']
                );

                $result = (string)(int)$result; // cast to '1' or '0'
                set_site_transient($cacheKey, $result, $cacheTime);
            } catch (Exception $e) {
                $result = false;
                // timeout or other DNS error
                // TODO: log or something else?
            }
        } else {
            $result = $ipResult;
        }

        return $result === '1';
    }

    public function adminMenu()
    {
        $screen = get_current_screen();
        $plugin = plugin_basename(__FILE__);
        $prefix = '';
        if (is_object($screen) && isset($screen->is_network)) {
            $prefix = $screen->is_network ? 'network_admin_' : '';
        }

        $hook = add_options_page('Tor Blocking Settings', 'VigilanTor Settings', 'manage_options', $this->_menuSlug, array(&$this, 'adminPage'));
        add_action('admin_init', array(&$this, 'registerSettings'));
        add_action('load-' . $hook, array(&$this, 'addHelpMenu'));
        add_filter("{$prefix}plugin_action_links_{$plugin}", array(&$this, 'pluginSettingsLink'), 10, 2);
    }

    public function registerSettings()
    {
        register_setting('vigilantor', 'vitor_block_comments');
        register_setting('vigilantor', 'vitor_hide_commentform');
        register_setting('vigilantor', 'vitor_block_trackbacks');
        register_setting('vigilantor', 'vitor_block_registration');
        register_setting('vigilantor', 'vitor_flag_registration');
        register_setting('vigilantor', 'vitor_block_login');
        register_setting('vigilantor', 'vitor_block_everything');
        register_setting('vigilantor', 'vitor_blocked_page');
        register_setting('vigilantor', 'vitor_enable_realtime');
        register_setting('vigilantor', 'vitor_realtime_timeout');
        register_setting('vigilantor', 'vitor_el_update_frequency');
        register_setting('vigilantor', 'vitor_use_captcha');
        register_setting('vigilantor', 'vitor_custom_blocklist');
        register_setting('vigilantor', 'vitor_custom_block_message');
        register_setting('vigilantor', 'vitor_custom_blocklist_enabled');

        // stats
        register_setting('vigilantor-stats', 'vitor_stat_comments');
        register_setting('vigilantor-stats', 'vitor_stat_trackbacks');
        register_setting('vigilantor-stats', 'vitor_stat_login');
        register_setting('vigilantor-stats', 'vitor_stat_registration');
        register_setting('vigilantor-stats', 'vitor_stat_blockedviews');
    }

    public function addHelpMenu()
    {
        get_current_screen()->add_help_tab( array(
                'id'      => 'overview',
                'title'   => __('Overview', 'vigilantor'),
                'content' => '<p>' . __('This plugin provides various options for blocking Tor users from performing certain actions on your WordPress site.', 'vigilantor') . '</p>' .
                    '<p>' . __('If you have installed this plugin, most likely you have suffered repeated abuse in one form or another, from brute force login attempts, to spam comments.  This plugin aims to help reduce these abuses by blocking Tor users from performing one or more actions on your site.  You can even block Tor users from accessing your entire site, but this should be used with discretion.', 'vigilantor'),
        ) );

        get_current_screen()->add_help_tab( array(
                'id'      => 'blocking-settings',
                'title'   => __('Blocking Settings', 'vigilantor'),
                'content' => '<p>' . __('This section allows you to configure what actions Tor users will be restricted from', 'vigilantor') . '</p>' .
                '<p><strong>' . __('Block Tor users from registering', 'vigilantor') . '</strong><br>' . __('With this option selected, a Tor user who attempts to register for your site will be blocked from registering.  They will receive an error message after filling in the registration form.  If registration is disabled on your WordPress site, there is no need to enable this option.', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Flag users who sign up using Tor', 'vigilantor') . '</strong><br>' . __('This option allows Tor users to register, but they will be flagged and show up on this page so their details can be reviewed.  Note: A flagged user is not restricted in any way.  This simply allows you to see or keep track of who registered using Tor so you can monitor their activity.', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Block Tor users from logging in', 'vigilantor') . '</strong><br>' . __('Prevents Tor users from logging in to your WordPress site.  This option is useful even with registration disabled because it will block login attempts to your WordPress admin panel and is helpful in preventing brute force login attempts.  Or, if one of your WordPress administrator accounts is compromised, the attacker will not be able to use Tor to hide their IP address when logging in.', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Block Tor users from commenting', 'vigilantor') . '</strong><br>' . __('Perhaps one of the most useful options, this will block comments if the comment is coming from a Tor IP address.  Very useful in preventing spam.', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Block pings &amp; trackbacks from Tor addresses', 'vigilantor') . '</strong><br>' . __('Similar to the block comments option, except this blocks pingbacks and trackbacks from Tor IP addresses.  This option has no effect if comments or trackbacks are disabled on your WordPress site.', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Block Tor users from all of WordPress', 'vigilantor') . '</strong><br>' . __('Use this option as a last resort or if you have a very good reason to use it.  With this enabled, Tor users will be unable to access to your entire WordPress site.  You can show a specific page to blocked users, or if "None" is selected, a simple block message will be displayed.  All pages and posts will return a 403 Forbidden response and no content will be displayed.', 'vigilantor') . '</p>',
        ) );

        get_current_screen()->add_help_tab( array(
                'id'      => 'detection-settings',
                'title'   => __('Detection Settings', 'vigilantor'),
                'content' => '<p>' . __('Controls how often to download exit list updates and whether or not to enable real-time checking', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Exit list update frequency', 'vigilantor') . '</strong><br>' . __('An exit list is a list of Tor IP addresses that connect Tor clients to the internet.  Only exit nodes connect to internet services like websites.  This setting controls how often to your WordPress site downloads the exit list used to detect Tor users.', 'vigilantor') . '</p>' .
                    '<p><strong>' . __('Enable real-time checking', 'vigilantor') . '</strong><br>' . sprintf(__('Real time checking uses DNS to query the %s to check the client IP address against active Tor exit nodes.  This option provides an accurate method of detecting Tor.  DNS lookups for each client IP address are cached for 5 minutes for efficiency.  The first visit to the site, or every 5 minutes thereafter may incur a small performance penalty since the plugin waits for the DNS request before allowing or denying the action from taking place.', 'vigilantor'), sprintf('<a href="https://www.torproject.org/projects/tordnsel.html.en" target="_blank">%s</a>', __('Tor DNS exit list service', 'vigilantor'))) . '</p>' .
                    '<p><strong>' . __('Real time lookup timeout', 'vigilantor') . '</strong><br>' . __('If the real time lookup is enabled, this controls how long to wait before timing out.  If the lookup times out, the downloaded exit list will be queried instead.', 'vigilantor') . '</p>',
        ) );
    }

    public function adminPage()
    {
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.', 'vigilantor') );
        }

        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
            $this->_scheduleUpdate();
        }

        get_current_screen()->add_help_tab( array(
                'id'      => 'overview',
                'title'   => __('Overview', 'vigilantor'),
                'content' => '<p>' . __('Permalinks are the permanent URLs to your individual pages and blog posts, as well as your category and tag archives. A permalink is the web address used to link to your content. The URL to each post should be permanent, and never change &#8212; hence the name permalink.', 'vigilantor') . '</p>' .
                '<p>' . __('This screen allows you to choose your default permalink structure. You can choose from common settings or create custom URL structures.', 'vigilantor') . '</p>' .
                '<p>' . __('You must click the Save Changes button at the bottom of the screen for new settings to take effect.', 'vigilantor') . '</p>',
        ) );

        get_current_screen()->add_help_tab( array(
                'id'      => 'common-settings',
                'title'   => __('Common Settings', 'vigilantor'),
                'content' => '<p>' . __('Many people choose to use &#8220;pretty permalinks,&#8221; URLs that contain useful information such as the post title rather than generic post ID numbers. You can choose from any of the permalink formats under Common Settings, or can craft your own if you select Custom Structure.', 'vigilantor') . '</p>' .
                '<p>' . __('If you pick an option other than Default, your general URL path with structure tags, terms surrounded by <code>%</code>, will also appear in the custom structure field and your path can be further modified there.', 'vigilantor') . '</p>' .
                '<p>' . __('When you assign multiple categories or tags to a post, only one can show up in the permalink: the lowest numbered category. This applies if your custom structure includes <code>%category%</code> or <code>%tag%</code>.', 'vigilantor') . '</p>' .
                '<p>' . __('You must click the Save Changes button at the bottom of the screen for new settings to take effect.', 'vigilantor') . '</p>',
        ) );

        $message_class = 'updated';
        $admin_message = '';

        $action = (isset($_GET['action']) ? $_GET['action'] : '');
        switch($action) {

            case 'update':
                $this->updateExitList();
                // fall through to default action

            default:
                if (($lastUpdate = get_option('vitor_exit_list_last_updated')) != false) {
                    try {
                        // wp overrides php date.timezone in admin...get WP timezone and create time strings
                        $tz         = get_option('timezone_string', 'GMT');
                        $tzoff      = get_option('gmt_offset', 0);
                        if (empty($tz)) {
                            $tz = timezone_name_from_abbr('', 3600 * $tzoff, 0);
                        }
                        $lastUpdate = new DateTime("@{$lastUpdate}");
                        $lastUpdate->setTimezone(new DateTimeZone($tz));
                        $updateDate = $lastUpdate->format('Y/m/d');
                        $updateTime = $lastUpdate->format('g:i:s a T');
                    } catch (Exception $ex) { /* some error with an invalid timezone */ }
                }
                $lastError = get_option('vitor_last_update_failure', '');

                $template = 'admin.phtml';
                break;
        }

        include dirname(__FILE__) . '/templates/' . $template;
    }

    public function getPluginSettingsUrl()
    {
        return network_admin_url() . 'options-general.php?page=' . $this->_menuSlug;
    }

    public function pluginSettingsLink($links)
    {
        $link = '<a href="' . $this->getPluginSettingsUrl() . '">' . __('Settings', 'vigilantor') . '</a>';
        array_unshift($links, $link);
        return $links;
    }

    public static function install()
    {
        $instance = self::run();
        $instance->_install();
    }

    public function enqueueUpdateScript()
    {
        $url = addslashes(get_site_url() . '?_vitor_action=update');
        echo "<script type='text/javascript'>jQuery.ajax({ url: '{$url}' });</script>\n";
    }

    public function _scheduleUpdate()
    {
        wp_clear_scheduled_hook('vitor_update_lists');
        $freq = get_option('vitor_el_update_frequency', '_10m');
        $time = mktime(date('H'), 0, 0, date('m'), date('d'), date('Y'));
        wp_schedule_event($time, $freq, 'vitor_update_lists');
    }

    private function _install()
    {
        $this->_scheduleUpdate();
        $this->updateExitList();
    }

    private function clearFlagAction()
    {
        $user_id = $_POST['user_id'];
        $error   = false;
        $message = '';

        if (empty($user_id)) {
            $error   = true;
            $message = __('No user ID given', 'vigilantor');
        } else {
            $deleted = $this->_wpdb->delete(
                $this->_wpdb->prefix . 'usermeta',
                array('user_id' => $user_id, 'meta_key' => 'vitor_flagged_registration'),
                array('%d', '%s')
            );

            if (!$deleted) {
                $error   = true;
                $message = __('Failed to delete flag from user ID', 'vigilantor');
            }
        }

        $response = array(
            'error'   => $error,
            'message' => $message,
        );

        header('Content-type: application/json');
        echo json_encode($response);
        wp_die();
    }

    public function _addSchedules($schedules)
    {
        $temp = $this->_updateFrequencies;
        unset($temp['_60m']);

        return array_merge($temp, $schedules);
    }

    private function findInExitList($ip_address)
    {
        return false !== $this->_arrayBinarySearch($ip_address, $this->getExitList());
    }

    private function findInCustomBlocklist($ip_address)
    {
        $list    = $this->getCustomBlocklist();
        $reverse = null;
        $found   = false;

        if (empty($list)) return false;

        foreach($list as $entry) {
            if ($this->isIp($entry)) {
                // IP address (exact or partial)
                if (strpos($entry, $ip_address) === 0) {
                    $found = true;
                    break;
                }
            } else {
                // hostname entry
                if (is_null($reverse)) {
                    $reverse = gethostbyaddr($ip_address);
                }

                if (strpos($entry, '.') === 0) {
                    // partial hostname match (e.g. .host.domain.com)
                    if (preg_match('/' . preg_quote($entry) . '$/i', $reverse) === 1) {
                        $found = true;
                        break;
                    }
                } else {
                    // exact hostname match
                    if (strtolower($entry) == strtolower($reverse)) {
                        $found = true;
                        break;
                    }
                }
            }
        }

        if ($found) {
            return true;
        }

        return false;
    }

    /**
     * Lazy IPv4 or IPv6 detection
     *
     * @param string $value  IP or hostname to check
     * @return boolean true if ipv4 or ipv6 value, false otherwise
     */
    private function isIp($value)
    {
        if (preg_match('/^(\d{1,3}\.){1,3}/', $value)) {
            // ipv4 partial
            return true;
        } elseif (strpos($value, ':') !== false) {
            // any ipv6
            return true;
        } else {
            // hostname
            return false;
        }
    }

    public function updateExitList()
    {
        if (get_site_transient('vitor_list_updating') !== false) {
            return ; // avoid race condition to update
        }

        set_site_transient('vitor_list_updating', '1', 30);

        $list = $this->_downloadExitList();
        if (!$list) return false;

        $lines = explode("\n", $list);
        unset($list);

        if ($lines === false || sizeof($lines) < 3) {
            return false;
        }

        $list = array();
        //$list[] = '127.0.0.1'; // dev testing

        foreach($lines as $idx => $line) {
            $ip = trim($line);
            if ($ip == '' || $ip[0] == '#') continue;
            $list[] = $ip;
        }

        unset($lines);
        sort($list);

        update_option('vitor_exit_list', $list);
        update_option('vitor_exit_list_last_updated', time());
    }

    public function getExitList()
    {
        if ($this->_exitList !== null) {
            return $this->_exitList;
        }

        $list = get_option('vitor_exit_list');

        if ($list === false) {
            $this->_downloadExitList();
            $list = get_option('vitor_exit_list');
        }

        if (is_array($list) && sizeof($list) > 0) {
            $this->_exitList = $list;
        }

        return $list;
    }

    public function getCustomBlocklist()
    {
        if ($this->_customBlockList !== null) {
            return $this->_customBlockList;
        }

        $list = trim(get_site_option('vitor_custom_blocklist', ''));

        if ($list == '') return array();

        $lines = explode("\n", $list);
        unset($list);
        $list = array();

        foreach($lines as $line) {
            $line = trim($line);
            if ($line == '' || substr($line, 0, 1) == '#') continue; // empty or comment line

            // remove entry comments
            list($line) = explode(' ', $line);
            $list[] = trim($line);
        }

        return $list;
    }

    public function filterUserAgent($ua)
    {
        return $ua . '; VigilanTor/' . VIGILANTOR_VERSION;
    }

    private function _downloadExitList()
    {
        $use_ssl = false;

        if (function_exists('curl_version')) {
            $ver = curl_version();
            if (in_array('https', $ver['protocols'])) {
                $use_ssl = true;
            }
        } else if (in_array('https', stream_get_wrappers())) {
            $use_ssl = true;
        }

        $url = ($use_ssl ? 'https:' : 'http:') . self::EXIT_LIST_UPDATE_URL;

        add_filter('http_headers_useragent', array(&$this, 'filterUserAgent'), 99, 1);

        $try     = true;
        $success = false;

        do {
            $req = new WP_Http();
            $res = $req->request($url, array('httpversion' => '1.1'));

            if (is_wp_error($res)) {
                if ($use_ssl) {
                    $use_ssl = false;
                    $url = 'http:' . self::EXIT_LIST_UPDATE_URL;
                    continue; // try again with http
                }
                $try = false;
            } else {
                $res     = $res['body'];
                $try     = false;
                $success = true;
            }
        } while($try);

        if (!$success && is_wp_error($res)) {
            update_option('vitor_last_update_failure', $res->get_error_message());
            $res = false;
        } else {
            delete_option('vitor_last_update_failure');
        }

        return $res;
    }

    private function getClientIpAddress()
    {
        // TODO: look for IP in other headers (e.g. X-Forwarded-For, X-Proxy-IP)
        return $_SERVER['REMOTE_ADDR'];
    }

    private function _getFlaggedRegistrations()
    {
        $query = "SELECT t1.user_id, t1.meta_value AS tor_ip, t2.user_login, t2.user_email, t2.user_registered "
                ."FROM %s t1 JOIN %s t2 ON (t1.user_id = t2.ID) "
                ."WHERE meta_key = 'vitor_flagged_registration'";

        $t1    = $this->_wpdb->prefix . 'usermeta';
        $t2    = $this->_wpdb->prefix . 'users';
        $users = $this->_wpdb->get_results(sprintf($query, $t1, $t2));

        return $users;
    }

    /**
     * Check the vigilantor token from cookie to see if temporary site access
     * is allowed.  If this function is called, the client is using Tor.
     *
     * @return bool true if the cookie is valid, false if not or not set
     */
    private function _checkCookie()
    {
        if (isset($_COOKIE['_vitor_access_token']) &&
            '' !== trim($_COOKIE['_vitor_access_token']) &&
            strpos($_COOKIE['_vitor_access_token'], ',') !== false)
        {
            $value = $_COOKIE['_vitor_access_token'];
            list($token_id, $value) = explode(',', $value, 2);
            $t = get_site_transient('vitor_' . $token_id);
            if ($t === $value) {
                $this->_setVitorCookie($token_id); // update cookie with new value (prevents sharing cookies)
                return true;
            }
        }

        return false;
    }

    private function _getCaptchaHtml()
    {
        $captcha_error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['v_post_action']) && $_POST['v_post_action'] == 'vitor_captcha') {
                $code       = '';    // code entered
                $captchaId  = '';    // captcha ID to check
                $valid      = false;

                // check that a captcha id was submitted with the form
                if (!empty($_POST['siwp_captcha_id'])) {
                    $captchaId = trim(stripslashes($_POST['siwp_captcha_id']));
                    $code      = trim(stripslashes($_POST['siwp_captcha_value']));

                    if (strlen($code) > 0) {
                        // validate the code if we received an input
                        if (siwp_validate_captcha_by_id($captchaId, $code) == true) {
                            $token_id    = $captchaId;
                            $this->_setVitorCookie($token_id);
                            $_SERVER['REQUEST_METHOD'] = 'GET';
                            wp_redirect($_SERVER['REQUEST_URI']);
                            exit;
                        }
                    }

                    if (!$valid) {
                        // captcha was typed wrong or was left empty (used by template)
                        $captcha_error = __('The security code entered was incorrect.', 'vigilantor');
                    }
                }
            }
        }

        ob_start();
        include 'templates/captcha.phtml';
        $captcha  = ob_get_contents();
        $captcha  = preg_replace("/\n+/", ' ', $captcha);
        $captcha  = preg_replace('/>\s+</', '><', $captcha);
        ob_end_clean();

        return $captcha;
    }

    private function _setVitorCookie($token_id)
    {
        $transient   = 'vitor_' . $token_id;
        $vitor_token = sha1(mt_rand(0xffff, 0x7fffffff) . $_SERVER['REMOTE_PORT'] . microtime(true));

        delete_site_transient($transient);
        set_site_transient($transient, $vitor_token, 3600);

        setcookie(
            '_vitor_access_token',
            $token_id . ',' . $vitor_token,
            null,
            COOKIEPATH,
            COOKIE_DOMAIN
        );
    }

    /**
     * Perform binary search of a sorted array.
     * Credit: http://php.net/manual/en/function.array-search.php#39115
     *
     * Tested by VigilanTor for accuracy and efficiency
     *
     * @param string $needle String to search for
     * @param array $haystack Array to search within
     * @return boolean|number false if not found, or index if found
     */
    private function _arrayBinarySearch($needle, $haystack)
    {
        $high = count($haystack);
        $low = 0;

        while ($high - $low > 1){
            $probe = ($high + $low) / 2;
            if ($haystack[$probe] < $needle){
                $low = $probe;
            } else{
                $high = $probe;
            }
        }

        if ($high == count($haystack) || $haystack[$high] != $needle) {
            return false;
        } else {
            return $high;
        }
    }

    private function _hasUdpSupport()
    {
        return in_array('udp', stream_get_transports());
    }
}
