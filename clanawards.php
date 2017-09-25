<?php
    require_once("wp-load.php");
    
    $args = array(
        'post_type'     =>  'clan',
        'posts_per_page' => -1,
        );
    
    $clans = get_posts($args);
    foreach ($clans as $clan) {
        $clan_members = get_post_meta($clan->ID, 'clan_members');
        
        $tot_land = 0;
        $tot_attacks = 0;
        foreach ($clan_members[0] as $member) {
            $land = get_user_meta($member, 'land', true);
            $attacks = get_user_meta($member, 'attacks_made', true);
            
            $tot_land+=$land;
            $tot_attacks+=$attacks;
        }
        update_post_meta($clan->ID, 'ub_total', $tot_land);
        update_post_meta($clan->ID, 'ua_total', $tot_attacks);
    }

        
        $args = array(
    'meta_key'     => 'ub_total',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',);
    
        $clans = get_posts($args);
    
    ?>
    
    <strong>United Boundaries</strong>
    <table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $land = get_post_meta($clan_ID, 'ub_total', true);?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
            </td>
            <td><?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
            </td>
        </tr>
    
        
    <?php } ?>
    </table>
    <br/>
    <?php
        
        $args = array(
    'meta_key'     => 'ua_total',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',);
    
        $clans = get_posts($args);
    
    ?>
    
    <strong>United Arms</strong>
    <table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $attacks = get_post_meta($clan_ID, 'ua_total', true);?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
            </td>
            <td><?php echo number_format($attacks, 0, ',', ' '); ?> attacks
            </td>
        </tr>
    <?php } ?>
    </table>
    <br/>
    
    
    
    <?php
        
        $args = array(
    'meta_key'     => 'clan_networth',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',);
    
        $clans = get_posts($args);
    
    ?>
    
    <strong>Networth</strong>
    <table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $networth = get_post_meta($clan_ID, 'clan_networth', true);?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
            </td>
            <td>$ <?php echo number_format($networth, 0, ',', ' '); ?>
            </td>
        </tr>
    <?php } ?>
    </table>

    <br/>
    <?php
        
        $args = array(
    'meta_key'     => 'clan_points',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',);
    
        $clans = get_posts($args);
    
    ?>
    
    <strong>Clan Points</strong>
    <table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $points = get_post_meta($clan_ID, 'clan_points', true);?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
            </td>
            <td><?php echo number_format($points, 0, ',', ' '); ?>pts
            </td>
        </tr>
    <?php } ?>
    </table>
    
    
    