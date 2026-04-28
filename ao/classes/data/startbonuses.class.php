<?php
	
class Startbonuses extends DataObject {

    public static function init() {

        Hooks::on('set_province_startbonus', 10, function($bonustype, $province) {
            switch($bonustype) {
                case 'offensive': $province->update('turns', $province->getTurns() + 75); break;
                case 'defensive': $province->update('land', $province->getLand() + 3500); break;
                case 'land': 
                	$timestamp = current_time('timestamp')+(86400*4);
                	$province->update('land', $province->getLand() + 5000); 
					$province->update('land_bonus_counter', $timestamp); 
                break;
                
                case 'finance': $province->update('money', $province->getMoney() + 400000); break;
                case 'shipping':
                    $province->update('land', $province->getLand() + 2500);
                    $province->update('money', $province->getMoney() + 250000);
                break;
            }
        });
        Hooks::on('get_province_building', 10, function(&$buildings, $id, $province) {
            if($province->hasStartingBonus('defensive')) {
                $buildings[$id]['life'] = round($buildings[$id]['life'] * 1.25);
            }
            if($province->hasStartingBonus('aggressive')) {
                $buildings[$id]['life'] = round($buildings[$id]['life'] * 0.66);
            }
        });
        Hooks::on('get_province_unit', 20, function(&$units, $id, $province) {
            if($province->hasStartingBonus('defensive')) {
                $units[$id]['life'] = round($units[$id]['life'] * 1.2);
            }
            if($province->hasStartingBonus('aggressive')) {
                $units[$id]['attack'] = round($units[$id]['attack'] * 1.25);
            }
        });
        Hooks::on('get_province_income', 20, function(&$income, $province) {
            if($province->hasStartingBonus('finance')) {
                $income = $income * 1.1;
            }
        });
        Hooks::on('get_province_max_deposit', 20, function(&$max_dep, $province) {
            if($province->hasStartingBonus('finance')) {
                $max_dep = $max_dep * 1.3;
            }
        });
        Hooks::on('get_province_research', 20, function(&$researches, $id, $province) {
            if($id == 'money_production' && $province->hasStartingBonus('finance')) {
                $researches[$id]['level_value'] = round($researches[$id]['level_value'] * 1.1);
            }
            if($id == 'market_discount' && $province->hasStartingBonus('shipping')) {
                $researches[$id]['level_value'] = $researches[$id]['level_value'] + 10;
            }
            if($province->hasStartingBonus('defensive')) {
                $researches[$id]['duration'] = round($researches[$id]['duration'] * 0.9);
            }
        });
        Hooks::on('get_province_shipping_discount', 20, function(&$return, $province) {
            if($province->hasStartingBonus('shipping')) {
                $return += .1;
            }
        });
        // @todo: offensive: Unit attack hooks
    }

    static $data = array(
        'offensive' => array(
            'icon' => 'fa fa-fire',
            'name' => 'Offensive',
            'description' => 'Gain twice the land and money during every ground, regular or air & sea attack.
                You will receive an additional 75 turns.',
        ),
        'defensive' => array(
            'icon' => 'fas fa-shield-alt',
            'name' => 'Defensive',
            'description' => '20% extra life for all defending units, 25% extra life for buildings,
                plus 10% time deduction when researching, plus 3 500m<sup>2</sup> of land.',
        ),
        'finance' => array(
            'icon' => 'fas fa-dollar-sign',
            'name' => 'Finance',
            'description' => 'Hourly income increased by 10%. Your bank capacity is raised by 30%.
                You will receive an additional $ 400 000',
        ),
        'shipping' => array(
            'icon' => 'fa fa-truck',
            'name' => 'Shipping',
            'description' => 'Missile orders ship 50% faster, plus ability to choose exact arrival time for units (up to 6 hours delayed),
                plus 10% default market discount (max 40% with research), 2 500 m<sup>2</sup> land and $250 000 money.',
        ),
        'aggressive' => array(
            'icon' => 'fa-solid fa-meteor',
            'name' => 'Aggressive',
            'description' => 'Every unit does an additional 20% extra attack damage when attacking. All buildings and units have 33% less life when attacked.'),
        'land' => array(
            'icon' => 'fa-solid fa-mountain',
            'name' => 'Land',
            'description' => 'Every four days you will receive 7 000m<sup>2</sup> land. Upon choosing this bonus you will receive 5 000m<sup>2</sup> land.',
        )
    );

}
