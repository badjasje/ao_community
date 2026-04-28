<?php
class Missiles extends DataObject {

    static public function get($key=null) {
        return (date('d-m')=='01-04' ? shuffle_assoc(parent::get($key)) : parent::get($key));
    }

    static $data = array(
        'nuke'      => array(
            'price'         =>  '125000',
            'networth'      =>  10,
            'normalname'    =>  'Nuclear Missile',
            'shortname'     =>  'Nuke',
            'shortatt'      =>  array('s','a','i','b','v'),
            'attacks'       =>  array('sea','air','inf','bld','veh'),
            'defends'       =>  array(''),
            'attack'        =>  27500,
            'type'          =>  'mis',
            'life'          =>  0
        ),
        'chemical'      => array(
            'price'         =>  '62500',
            'networth'      =>  10,
            'normalname'    =>  'Chemical Missile',
            'shortname'     =>  'Chem',
            'shortatt'      =>  array('s','a','v'),
            'attacks'       =>  array('sea','air','veh'),
            'defends'       =>  array(''),
            'attack'        =>  15000,
            'type'          =>  'mis',
            'life'          =>  0
        ),
        'bio'       => array(
            'price'         =>  '43750',
            'networth'      =>  10,
            'normalname'    =>  'Biochemical Missile',
            'shortname'     =>  'Bio',
            'shortatt'      =>  array('i'),
            'attacks'       =>  array('inf'),
            'defends'       =>  array(''),
            'attack'        =>  6000,
            'type'          =>  'mis',
            'life'          =>  0
        ),
        'moab'      => array(
            'price'         =>  '57000',
            'networth'      =>  10,
            'normalname'    =>  'MOAB',
            'shortname'     =>  'MOAB',
            'shortatt'      =>  array('b'),
            'attacks'       =>  array('bld'),
            'defends'       =>  array(''),
            'attack'        =>  7200,
            'type'          =>  'mis',
            'life'          =>  0
        ),
        'empmis'    => array(
            'price'         =>  '65000',
            'networth'      =>  10,
            'normalname'    =>  'EMP Missile',
            'shortname'     =>  'EMP Missile',
            'shortatt'      =>  array('b'),
            'attacks'       =>  array('N/A'),
            'defends'       =>  array(''),
            'attack'        =>  0,
            'type'          =>  'mis',
            'life'          =>  0
        ),
    );

}
