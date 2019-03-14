<?php
/*
Plugin Name: WP Login Form with reCAPTCHA3
Plugin URI: http://jaapbroeders.com
Description: Add Google's reCAPTCHA3 to WordPress Login
Version: 1.0
Author: Jaap Broeders
Author URI: http://jaapbroeders.com
License: GPL2
*/

class reCAPTCHA3_Login_Form {

    private $public_key, $private_key;

    public function __construct() {
        $this->public_key  = '6LfBbJcUAAAAAG_XKSkmzoSyY8-hte8IkL-7AEzx';
        $this->private_key = '6LfBbJcUAAAAAF_IThccp2VeZlxvTPTYZF5MJV24';

        // adds the captcha to the login form
        add_action('login_form', array($this, 'captcha_display'));

        // authenticate the captcha answer
        add_action('wp_authenticate_user', array($this, 'validate_captcha_field'), 10, 2);
    }

    public function captcha_display() {
        ?>
        <input type="hidden" name="recaptcha_response_field" id="recaptcha_response_field" value="">
        <script src="https://www.google.com/recaptcha/api.js?render=<?=$this->public_key;?>"></script>
        <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?=$this->public_key;?>', {action:'homepage'}).then(function(token) {
                jQuery('#recaptcha_response_field').val(token);
            });
        });
        </script>
        <?php
    }

    /**
     * Verify the captcha answer
     *
     * @param $user string login username
     * @param $password string login password
     *
     * @return WP_Error|WP_user
     */
    public function validate_captcha_field($user, $password) {
        if (!isset($_POST['recaptcha_response_field']) || empty($_POST['recaptcha_response_field']) ) {
            return new WP_Error('empty_captcha', 'CAPTCHA should not be empty');
        }
        if(isset($_POST['recaptcha_response_field'])) {
            $response = $this->recaptcha_response();
            if($response['success'] !== true) {
                return new WP_Error('invalid_captcha', 'CAPTCHA response was incorrect');
            }
        }
        return $user;
    }

    /**
     * Get the reCAPTCHA API response.
     *
     * @return string
     */
    public function recaptcha_response() {
        $response  = isset($_POST['recaptcha_response_field']) ? esc_attr($_POST['recaptcha_response_field']) : '';
        $remote_ip = $_SERVER["REMOTE_ADDR"];
        $post_body = array('secret' => $this->private_key, 'remoteip' => $remote_ip, 'response' => $response);
        return $this->recaptcha_post_request($post_body);
    }

    /**
     * Send HTTP POST request and return the response.
     *
     * @param $post_body array HTTP POST body
     *
     * @return array
     */
    public function recaptcha_post_request( $post_body ) {
        $args = array('body' => $post_body);
        $request = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', $args);
        $response_body = wp_remote_retrieve_body($request);
        $response = json_decode($response_body, true);
        return $response;
    }
}
$reCAPTCHA3_Login_Form = new reCAPTCHA3_Login_Form();