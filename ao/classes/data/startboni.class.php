<?php
class Startboni extends DataObject {

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
            'description' => 'Hourly income increased by 10%. Your bank capacity is raised by 50%.
                You will receive an additional $ 400 000',
        ),
        'shipping' => array(
            'icon' => 'fa fa-truck',
            'name' => 'Shipping',
            'description' => 'Missile orders ship 50% faster, plus ability to choose exact arrival time for units (up to 6 hours delayed),
                plus 10% default market discount (max 40% with research), 2 500 m<sup>2</sup> land and $250 000 money.',
        )
    );

}
