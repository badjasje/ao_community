<?php

class Bonus extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);

        if(!empty($this->post_author)) {
            $this->setPropertiesFromArray(array(
                'province_id' => intval($this->post_author), 'end_time' => intval($this->get('time_attacked')) + (86400*2)
            ));
        }
    }

    public function isUsed() {
        return $this->get('bonus_used') == 'yes';
    }

    public function timeLeft($format=false) {
        $diff = $this->get('end_time') - current_time('timestamp');
        return ($format ? Format::time_diff($diff) : $diff);
    }

    public function money($format=false) {
        $n = round($this->get('bonus_money'));
        return ($format ? Format::money($n) : $n);
    }

    public function turns($format=false) {
        $n = intval($this->get('bonus_turns'));
        return ($format ? Format::turns($n) : $n);
    }

    // Used manualy and in cron
    public function receive() {
        if($this->isUsed()) return false;
        $province = Province::make($this->get('province_id'));
        $province->update('money', $province->getMoney() + $this->money());
        $province->update('turns', $province->getTurns() + $this->turns());
        $this->update('bonus_used', 'yes');

        /*$file = 'bonuslog.txt';
        $current = file_get_contents($file);
        $turns_newest = $province->getTurns();
        $current .= current_time('G:i:s | d-m-Y').PHP_EOL;
        $current .= "User ID: ". $this->get('province_id') ." Event ID: ". $this->get('id') . PHP_EOL;
        //$current .= "IP Address: ". get_user_ip_address()."\n";
        //$current .= "New Money: ". $money_new ." Old turns: ". $turns ." | New Turns: ". $turns_newest ."\n\n";
        file_put_contents($file, $current);*/
        return true;
    }

}