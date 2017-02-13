<?php
$el_contact_title =  $el_class = '';
global $post;
extract( shortcode_atts( array(
    'el_contact_title' => '',
    'el_class' => '',
), $atts ) );
if(empty($css)) $css = '';
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_text_column wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>

<div id="buddypress" class="<?php echo esc_attr($css_class); if(!empty($el_class)) echo esc_attr($el_class); ?>">
    <div class="wpb_wrapper">
        <div class="title-wrapper">
            <h3 class="widget-title"><i class="fa fa-envelope"></i> <?php if(!empty($el_contact_title)) echo esc_attr($el_contact_title); ?></h3>
            <div class="clear"></div>
        </div>
        <div class="wcontainer">
<?php

        if(isset($_POST['submitted'])) {

    if(trim($_POST['contactName']) === '') {

        $nameError = esc_html__('Please enter your name.', 'crystalskull');

        $hasError = true;

    } else {

        $name = trim($_POST['contactName']);

    }

    if(trim($_POST['email']) === '')  {

        $emailError = esc_html__('Please enter your email address.', 'crystalskull');

        $hasError = true;

    } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {

        $emailError = esc_html__('You entered an invalid email address.', 'crystalskull');

        $hasError = true;

    } else {

        $email = trim($_POST['email']);

    }

    if(trim($_POST['comments']) === '') {

        $commentError = esc_html__('Please enter a message.', 'crystalskull');

        $hasError = true;

    } else {

        if(function_exists('stripslashes')) {

            $comments = stripslashes(trim($_POST['comments']));

        } else {

            $comments = trim($_POST['comments']);

        }

    }

    if(!isset($hasError)) {

        $emailTo = of_get_option('contact_email');

        if (!isset($emailTo) || ($emailTo == '') ){

            $emailTo = of_get_option('email');

        }

        $sub = $_POST['subject'];

        $subject = '[PHP Snippets] From '.$name;

        $body = "Name: $name \n\nEmail: $email \n\nSubject: $sub \n\nComments: $comments";

        $headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

        wp_mail($emailTo, $subject, $body, $headers);

        $emailSent = true;

    }

}
        ?>



           <?php  if(isset($emailSent) && $emailSent == true) { ?>

                            <div class="thanks">

                                <p><?php esc_html_e("Thanks, your email was sent successfully.", 'crystalskull'); ?></p>

                            </div>

                        <?php } else { ?>



                            <?php if(isset($hasError) || isset($captchaError)) { ?>

                                <p class="error"><?php esc_html_e("Sorry, an error occured.", 'crystalskull'); ?><p>

                            <?php } ?>

                        <form action="<?php the_permalink(); ?>" id="contactForm" class="contact" method="post">

                            <ul class="contactform controls">

                            <li class="input-prepend">



                                <input type="text" name="contactName" placeholder="Name*" id="contactName" value="<?php if(isset($_POST['contactName'])) echo esc_attr($_POST['contactName']);?>" class="required requiredField" />
                                <?php if(!isset($nameError))$nameError = ''; ?>
                                <?php if($nameError != '') { ?>

                                    <span class="error"><?php $nameError;?></span>

                                <?php } ?>

                            </li>

                            <li class="input-prepend">


                                <input type="text" placeholder="Email*" name="email" id="email" value="<?php if(isset($_POST['email']))  echo esc_attr($_POST['email']);?>" class="required requiredField email" />
                                <?php if(!isset($emailError))$emailError = ''; ?>
                                <?php if($emailError != '') { ?>

                                    <span class="error"><?php $emailError;?></span>

                                <?php } ?>

                            </li>

                            <li class="input-prepend">


                                <input type="text" placeholder="Subject" name="subject" id="subject" value="<?php if(isset($_POST['subject']))  echo esc_attr($_POST['subject']);?>" class="subject" />

                            </li>

                            <li class="input-prepend">

                                <textarea name="comments" placeholder="Your message*" id="commentsText" rows="10" cols="30" class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo esc_attr($_POST['comments']); } } ?></textarea>
                                <?php if(!isset($commentError))$commentError = ''; ?>
                                <?php if($commentError != '') { ?>

                                    <span class="error"><?php $commentError;?></span>

                                <?php } ?>

                            </li>

                            <li>

                                   <input type="submit" class="button-green button-small"  value="<?php echo esc_html__("Send email", 'crystalskull'); ?>" />

                            </li>

                        </ul>

                        <input type="hidden" name="submitted" id="submitted" value="true" />

                    </form>

                <?php } ?>

    </div>
    </div>
</div>