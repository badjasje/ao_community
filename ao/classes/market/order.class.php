<?php
class Order extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);
        $this->setPropertiesFromArray(array('province_id' => intval($postData->post_author)));
        // post_date
        // order_type = units, satellite, ?
        // order_value
        // units: unit_type, amount_ordered, user_placed_id, time_placed, delivery_time
    }

    // Used by market-cronjob and devfunds ajax call
    public function end() {

        $province = Province::make($this->get('province_id'));
        $unit_type = $this->get('unit_type');

        if($this->get('order_type') == 'units') {
            $unit = Units::get($unit_type);
            if(!$unit) return false;
            $ownedunits = $province->getUnitsNum($unit_type);
            $units_in_this_order = intval($this->get('amount_ordered'));
            $total_units_on_order = intval($province->get($unit_type.'_ordered'));
            $province->update($unit_type.'_ordered', $total_units_on_order - $units_in_this_order);
            $province->update($unit_type.'_owned', $units_in_this_order + $ownedunits);
            wp_trash_post($this->get('id'));
        }

        if($this->get('order_type') == 'satellite') {
            if(!$province->hasResearchMinimalLevel('satellite_construction', 1)) return false; // User cannot build sats
            if($province->getSatelliteNum() > 0) return false; // Province can only have one sattelite (for now)
            $satellite = Satellites::get($unit_type);
            if(!$satellite) return false; // Satellite does not exist

            $days = Settings::get('satellite_construction_1_endlife');
            if ($province->hasResearchMinimalLevel('satellite_construction', 2)) $days = Settings::get('satellite_construction_2_endlife');
            $province->update('sat_owned', $unit_type);
            $province->update('sat_in_progress', 0);
            $province->update('sat_endlife', current_time('timestamp') + ($days * 86400));
            wp_trash_post($this->get('id'));
        }

        return true;
    }
}