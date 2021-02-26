<?php

class Report extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);

        if(!empty($this->post_author)) {
            $this->setPropertiesFromArray(array('province_id' => intval($this->post_author)));
        }

        $this->enhanced = 0;
        $this->entities = array();
        $spy_array = maybe_unserialize($this->get('spy_array'));
        if(is_array($spy_array)) {
            foreach($spy_array as $name => $amount) {
                if($name == 'enhance') { $this->enhanced = $amount; continue; }
                $this->entities[$name] = $amount;
            }
        }
    }

    static function create($data) {

    }

    function getData($format=false) { // Used in api
        return array(
            'type' => $this->getType($format),
            'land_registered' => $this->getLandRegistered($format),
            'land_current' => $this->getLandCurrent($format),
            'networth_registered' => $this->getNetworthRegistered($format),
            'networth_current' => $this->getNetworthCurrent($format),
            'enhanced' => $this->getEnhanced($format),
            'date' => $this->getDate($format),
            'entities' => $this->getEntities($format),
        );
    }

    function getType() {
        return ($this->get('spy_type') == 'spy' ? 'units' : 'buildings');
    }

    function getLandRegistered($format=false) {
        $n = intval($this->get('spied_land'));
        return ($format ? Format::land($n) : $n);
    }
    function getLandCurrent($format=false) {
        $spied = Province::make($this->spied_id);
        return $spied->getLand($format);
    }

    function getNetworthRegistered($format=false) {
        $n = intval($this->get('spied_nw'));
        return ($format ? Format::networth($n) : $n);
    }
    function getNetworthCurrent($format=false) {
        $spied = Province::make($this->spied_id);
        return $spied->getNetworth($format);
    }

    function getEnhanced($format=false) {
        return ($format ? Format::number($this->enhanced) : $this->enhanced);
    }

    function getEntities($format=false) { // buildings or units
        return $this->entities;
    }

    function getDate($format=false) { // Original: Y-m-d H:i:s
        $d = strtotime($this->post_date);
        return ($format ? Format::date($d) : $d);
    }
}