<?php
class Researches extends DataObject {

    public static function get($key=null) {
        return (date('d-m')=='01-04' ? shuffle_assoc(parent::get($key)) : parent::get($key));
    }

    public static function init() {

        Hooks::on('get_province_building', 20, function(&$buildings, $id, $province) {
            if(!in_array($id, array('powerplant','advancedpowerplant'))) return;
            if ($province->hasResearchMinimalLevel('powerplant_efficiency',1)) {
                $buildings[$id]['life'] = round($buildings[$id]['life'] * 1.5);
                $buildings[$id]['powerprod'] = round($buildings[$id]['powerprod'] * 1.5);
                $buildings[$id]['description'] = 'Produces ' . $buildings[$id]['powerprod'] .' power.';
            }
        });
        Hooks::on('get_province_buildings_per_turn', 20, function(&$bbt, $province) {
            $ee = $province->getResearches('engineering_effectiveness');
            if($ee['level']>0) $bbt = $ee['level'.$ee['level'].'_bbt'];
        });
        Hooks::on('get_province_income', 10, function(&$income, $province) {
            $mp = $province->getResearches('money_production');
            if($mp['level']>0) $income = $mp['level'.$mp['level'].'_value'];
        });
        Hooks::on('get_province_interest_rates', 10, function(&$rates, $province) {
            $bm = $province->getResearches('bank_management');
            $bank_level = $bm['level'];
            $extra_interest = 0;
            for($i=0; $i<$bank_level; $i++) {
                $extra_interest += (isset($bm['level'.$bank_level.'_interest']) ? $bm['level'.$bank_level.'_interest'] : 0);
            }
            foreach($rates as $length => $rate) {
                $rates[$length] = $rate + $extra_interest;
            }
        });
        Hooks::on('get_province_max_deposit', 10, function(&$max_dep, $province) {
            $bm = $province->getResearches('bank_management');
            if($bm['level']>0) $max_dep = $bm['level'.$bm['level'].'_deposit'];
        });
        Hooks::on('get_province_satellite', 10, function(&$satellites, $id, $province) {
            $satellites[$id]['days'] = 11;
            if($province->hasResearchMinimalLevel('satellite_construction', 2)) {
                $satellites[$id]['days'] = 16;
            }
            if($province->hasResearchMinimalLevel('satellite_construction', 3)) {
                $satellites[$id]['price'] = $satellites[$id]['price'] * .8;
            }
        });
        Hooks::on('get_deposit_available_amount', 10, function(&$amount, $deposit) {
            if(!$deposit->unlocked() || $deposit->timeLeft() <=0 ) return;
            $province = Province::make($deposit->get('province_id'));
            $bm = $province->getResearches('bank_management');
            $bank_level = $bm['level'];
            if($bank_level >= 2) {
                $amount = $amount * $bm['level'.$bank_level.'_withdraw'];
            }
        });
        Hooks::on('get_deposit_unlocked', 10, function(&$return, $deposit) {
            $province = Province::make($deposit->get('province_id'));
            $bm = $province->getResearches('bank_management');
            $bank_level = $bm['level'];
            if($bank_level >= 2 && $deposit->get('deposit_placed') + $bm['level2_time'] <= current_time('timestamp')) {
                $return = true;
            }
        });

        // @todo: missile_accuracy
        // @todo: thieving_effectiveness

        Hooks::on('get_province_shipping_discount', 10, function(&$return, $province) {
            $md = $province->getResearches('market_discount');
            if($md['level'] == 1) $return += ($md['level1_value']/100);
            if($md['level'] == 2) $return += ($md['level2_value']/100);
        });
        Hooks::on('get_province_shipping_time', 10, function(&$return, $province) {
            $st = $province->getResearches('shipping_time');
            if($st['level'] == 1) $return = 9;
            if($st['level'] == 2) $return = 6;
        });
    }

    static $data = array(
        // Increase housing of buildings with 10%
        // Increase buildings per 1m2 land
        // Increase units_per_turn
        // Cheaper missiles
        // Build a second sattelite? (oh snap)
        // Remove 30% nw
        // Add 30% nw
        // Bigger nw-range then 1.4
        'money_production'          => array(
            'name'                  => 'Money production',
            'description'           => 'Increases hourly income',
            'level1'                => 'Income increased to {value} per hour',
            'level1_value'          => 25000,
            'level2'                => 'Income increased to {value} per hour',
            'level2_value'          => 35000,
            'maxlevel'              => 2,
            'duration'              => 15,
        ),
        'engineering_effectiveness' => array(
            'name'                  => 'Engineering effectiveness',
            'level1'                => '10 buildings built per turn.',
            'level1_bbt'            => 10,
            'level2'                => '15 buildings built per turn.',
            'level2_bbt'            => 15,
            'maxlevel'              => 2,
            'description'           => 'Increases the amount of buildings built per turn',
            'duration'              => 10,
        ),
        'bank_management'           => array(
            'name'                  => 'Bank management',
            'level1'                => 'Bank stores up to $ 3 500 000 and bank interest increased by 0,5%',
            'level1_deposit'        => 350000,
            'level1_interest'       => 0.5,
            'level2'                => 'Bank stores up to $ 4 500 000 and bank interest increased by 0,5% + option to withdraw money after 12 hours with no interest but with a 50% fee',
            'level2_withdraw'       => 0.5,
            'level2_time'           => (60*60*24),
            'level2_deposit'        => 450000,
            'level2_interest'       => 0.5,
            'level3'                => 'Bank stores up to $ 5 000 000 and bank interest increased by 0,75% + the fee for early withdrawals is now 25%',
            'level3_withdraw'       => 0.75,
            'level3_deposit'        => 500000,
            'level3_interest'       => 0.75,
            'maxlevel'              => 3,
            'description'           => 'Increases the amount of money you can bank',
            'duration'              => 10,
        ),
        'powerplant_efficiency'     => array(
            'name'                  => 'Powerplant efficiency',
            'level1'                => 'Power Plants life and power produced increased by 50%',
            'maxlevel'              => 1,
            'description'           => 'Power Plants life and power produced increased by 50%',
            'duration'              => 5,
        ),
        'missile_accuracy'          => array(
            'name'                  => 'Missile accuracy',
            'level1'                => '45% chance to hit target',
            'level2'                => '90% chance to hit target',
            'level3'                => 'Only 1 missile silo can be sabotaged by a saboteur. Decreases the chance of sabotaging your missile silo by 10%.',
            'maxlevel'              => 3,
            'description'           => 'Increases missile accuracy',
            'duration'              => 10,
        ),
        'satellite_construction'    => array(
            'name'                  => 'Satellite technology',
            'level1'                => 'Allows you to build satellites',
            'level2'                => 'Extends the maximum orbit time of satellites by 5 days',
            'level3'                => 'Construction cost of satellites reduced by 20%',
            'maxlevel'              => 3,
            'description'           => 'Enables you to build sattelites',
            'duration'              => 10,
        ),
        'thieving_effectiveness'    => array(
            'name'                  => 'Thief education',
            'level1'                => 'Thieves steal two times the amount of money',
            'level2'                => 'Thieves steal three times the amount of money',
            'level3'                => 'Thieves steal four times the amount of money',
            'maxlevel'              => 3,
            'description'           => 'Enables your thieves to steal more money',
            'duration'              => 5,
        ),
        'shipping_time'             => array(
            'name'                  => 'Market shipping time',
            'level1'                => 'Shipping time reduced to 9 hours',
            'level2'                => 'Shipping time reduced to 6 hours',
            'maxlevel'              => 2,
            'description'           => 'Decreases shipping time from the market',
            'duration'              => 5,
        ),
        'market_discount'           => array(
            'name'                  => 'Market discount',
            'level1'                => '{value}% discount on all units purchased from the market',
            'level1_value'          => 15,
            'level2'                => '{value}% discount on all units purchased from the market',
            'level2_value'          => 30,
            'maxlevel'              => 2,
            'description'           => 'Decreases market prices',
            'duration'              => 5,
        ),

    );
}
