<?php
class Order extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);
        if(!empty($this->post_author)) {
            $this->setPropertiesFromArray(array('province_id' => intval($this->post_author)));
        }
        $unit_type = $this->get('unit_type');

        if($this->type() == 'units') {
            $unit = Units::get($unit_type);
            if(!!$unit) $this->set('title', $unit['normalname']);
        }
        if($this->type() == 'satellite') {
            $satellite = Satellites::get($unit_type);
            if(!!$satellite) $this->set('title', $satellite['name']);
        }
        if($this->type() == 'missile') {
            $missile = Missiles::get($unit_type);
            if(!!$missile) $this->set('title', $missile['normalname']);
        }
    }

    static function create($data) {
        $timestamp = current_time('timestamp');
        $new_order_id = wp_insert_post(array(
            'post_title' => $data['title'], 'post_status' => 'publish', 'post_type' => 'market_order', 'post_author' => $data['province_id']
        ));
        foreach($data as $key => $value) {
            if(in_array($key, array('title', 'province_id'))) continue;
            update_field($key, $value, $new_order_id);
        }
        return self::make($new_order_id);
    }

    public function title($format=false) {
        return $this->get('title');
    }

    public function type() {
        return $this->get('order_type'); // @todo: should be checked with possible types
    }

    public function amount() {
        return intval($this->get('amount_ordered'));
    }

    public function timeLeft($format=false) {
        $diff = intval($this->get('delivery_time')) - current_time('timestamp');
        return ($format ? Format::time_diff($diff) : $diff);
    }

    public function cashback($format=false) {
        $n = round(intval($this->get('order_value')) * Settings::get('order_cancel_cashback'));
        if ($this->type() == 'satellite') {
            $sat = Satellites::get($this->get('unit_type'));
            $n = (!!$sat ? round($sat['price'] * Settings::get('order_cancel_cashback')) : 0);
        }
        return ($format ? Format::money($n) : $n);
    }

    // Used by market, missile and satellite
    public function cancel() {
        if($this->get('post_status') != 'publish') return 'Order is not active';

        $province = Province::make($this->get('province_id'));
        if(empty($province->get('id'))) return 'Province not found';

        $unit_type = $this->get('unit_type');
        $units_ordered = $this->amount();
        $total_units_ordered = $province->get($unit_type.'_ordered');
        if($total_units_ordered < $units_ordered) $units_ordered = $total_units_ordered;
        $province->update($unit_type.'_ordered', $total_units_ordered - $units_ordered);
        if ($this->type() == 'satellite') {
            $province->update('sat_in_progress', 0);
        }
        $province->update('money', $province->getMoney() + $this->cashback());
        wp_trash_post($this->get('id'));
        return true;
    }

    // Used by market-cronjob and devfunds ajax call
    public function end() {

        $province = Province::make($this->get('province_id'));
        $unit_type = $this->get('unit_type');
        if($this->type() == 'units') {
            $unit = Units::get($unit_type);
            if(!$unit) return false; // Unit does not exist

            $ownedunits = $province->getUnitsNum($unit_type);
            $units_in_this_order = $this->amount();
            $total_units_on_order = intval($province->get($unit_type.'_ordered'));
            $province->update($unit_type.'_ordered', $total_units_on_order - $units_in_this_order);
            $province->update($unit_type.'_owned', $units_in_this_order + $ownedunits);
            wp_trash_post($this->get('id'));
        }

        if($this->type() == 'satellite') {
            if(!$province->hasResearchMinimalLevel('satellite_construction', 1)) return false; // User cannot build sats
            if($province->getSatelliteNum() != 0) return false; // Province can only have one sattelite (for now)
            $satellite = $province->getSatellites($unit_type);
            if(!$satellite) return false; // Satellite does not exist
            $province->update('sat_owned', $unit_type);
            $province->update('sat_in_progress', 0);
            $province->update('sat_endlife', current_time('timestamp') + ($satellite['days'] * 86400));
            wp_trash_post($this->get('id'));
        }

        if($this->type() == 'missile') {
            $missile = Missiles::get($unit_type);
            if(!$missile) return false; // Missile does not exist

            $ownedMissiles = $province->getMissiles($unit_type);
            $missiles_in_this_order = $this->amount();
            $total_missiles_on_order = intval($province->get($unit_type.'_ordered'));
            $province->update($unit_type.'_ordered', $total_missiles_on_order - $missiles_in_this_order);
            $province->update($unit_type.'_owned', $missiles_in_this_order + $ownedMissiles['num']);
            wp_trash_post($this->get('id'));
        }

        return true;
    }
}