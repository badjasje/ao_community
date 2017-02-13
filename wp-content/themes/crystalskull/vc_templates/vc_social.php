<?php
$el_social_title = $el_social_twitter = $el_social_envato = $el_social_facebook = $el_social_steam = $el_social_pinterest =
$el_social_google = $el_social_dribbble = $el_social_vimeo = $el_social_youtube = $el_social_twitch = $el_social_rss = '';

extract( shortcode_atts( array(
    'el_social_title' => '',
    'el_social_twitter' => '',
    'el_social_facebook' => '',
    'el_social_steam' => '',
    'el_social_twitch' => '',
    'el_social_google' => '',
    'el_social_dribbble' => '',
    'el_social_vimeo' => '',
    'el_social_youtube' => '',
    'el_social_linkedin' => '',
    'el_social_envato' => '',
    'el_social_rss' => '',
), $atts ) );

?>

<div id="buddypress" >
    <div class="wpb_wrapper" >
        <div class="title-wrapper">
            <h3 class="widget-title"><i class="fa fa-rss"></i> <?php if(!empty($el_social_title)) echo esc_attr($el_social_title); ?></h3>
            <div class="clear"></div>
        </div>
        <ul class="wcontainer socialb-wrapper">

			<?php if(!empty($el_social_envato)){ ?>
            <li><a href="<?php echo esc_attr($el_social_envato); ?>" target="_blank" class="s-envato" data-toggle="tooltip" data-original-title="DeviantArt"><i class="fa fa-deviantart"></i></a></li>
            <?php } ?>

        	<?php if(!empty($el_social_rss)){ ?>
        	<li><a href="<?php echo esc_attr($el_social_rss); ?>" target="_blank" class="s-rss" data-toggle="tooltip" data-original-title="RSS"><i class="fa fa-rss"></i></a></li>
        	<?php } ?>

        	<?php if(!empty($el_social_google)){ ?>
        	<li><a href="<?php echo esc_attr($el_social_google); ?>" target="_blank" class="s-google" data-toggle="tooltip" data-original-title="Google+"><i class="fa fa-google-plus"></i></a></li>
        	<?php } ?>

        	<?php if(!empty($el_social_youtube)){ ?>
        	<li><a href="<?php echo esc_attr($el_social_youtube); ?>" target="_blank" class="s-youtube" data-toggle="tooltip" data-original-title="Youtube"><i class="fa fa-youtube"></i></a></li>
        	<?php } ?>

        	<?php if(!empty($el_social_dribbble)){ ?>
            <li><a href="<?php echo esc_attr($el_social_dribbble); ?>" target="_blank" class="s-dribbble" data-toggle="tooltip" data-original-title="Dribbble"><i class="fa fa-dribbble"></i></a></li>
            <?php } ?>

            <?php if(!empty($el_social_twitter)){ ?>
            <li><a href="<?php echo esc_attr($el_social_twitter); ?>" target="_blank" class="s-twitter" data-toggle="tooltip" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li>
            <?php } ?>

            <?php if(!empty($el_social_vimeo)){ ?>
            <li><a href="<?php echo esc_attr($el_social_vimeo); ?>" target="_blank" class="s-vimeo" data-toggle="tooltip" data-original-title="Vimeo"><i class="fa fa-vimeo-square"></i></a></li>
            <?php } ?>

            <?php if(!empty($el_social_facebook)){ ?>
            <li><a href="<?php echo esc_attr($el_social_facebook); ?>" target="_blank" class="s-facebook" data-toggle="tooltip" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li>
            <?php } ?>

        	<?php if(!empty($el_social_twitch)){ ?>
            <li><a href="<?php echo esc_attr($el_social_twitch); ?>" target="_blank" class="s-twitch" data-toggle="tooltip" data-original-title="Twitch"><i class="fa fa-twitch"></i></a></li>
            <?php } ?>

        	<?php if(!empty($el_social_steam)){ ?>
            <li><a href="<?php echo esc_attr($el_social_steam); ?>" target="_blank" class="s-steam" data-toggle="tooltip" data-original-title="Steam"><i class="fa fa-steam"></i></a></li>
            <?php } ?>









        </ul>

    </div>
</div>