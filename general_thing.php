<?php
    
    require_once("wp-load.php");


$clan_points = (20000/1100)+((sqrt(20000)/25) * ((sqrt(200000[0]*1.5)/4.1)/100));
 $clan_points = ceil($clan_points);
 
 echo $clan_points;
/*
update_post_meta( 890317, 'bonus_level', 0 );


/*
$querystr = "
           	SELECT wposts.* 
			FROM 
				$wpdb->posts wposts,
				$wpdb->postmeta wpostmeta,
				$wpdb->postmeta wpostmetadmg 
			WHERE wposts.ID = wpostmeta.post_id 
				AND wposts.post_type = 'event_local'
			
				AND wpostmeta.meta_key = 'war_status'
				AND wpostmeta.meta_value IN('mutual','incoming','outgoing')
				
				AND wpostmetadmg.meta_key = 'nw_damage_defender'
				AND wpostmetadmg.meta_value >= 23
				
			ORDER BY wpostmetadmg.meta_value DESC
			LIMIT 300
         ";
         
         
    
    
 $pageposts = $wpdb->get_results($querystr);

    

echo '<pre>';
print_r($pageposts);
echo '</pre>';	


