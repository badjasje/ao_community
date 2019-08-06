<?php
class Deposit extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);

        if(!empty($this->post_author)) {
            $this->setPropertiesFromArray(array(
                'province_id' => intval($this->post_author),
                'end_time' => intval($this->post_title),
                'status' => $this->post_status,
            ));
        }
    }

    static function create($data) {
        $timestamp = current_time('timestamp');
        $RELEASE_DATE = $timestamp + ($data['length'] * (60*60*24));
        $args = array('post_title' => $RELEASE_DATE, 'post_status' => 'publish', 'post_type' => 'deposit', 'post_author' => $data['province_id']);
        $new_order_id = wp_insert_post($args);
        update_post_meta($new_order_id, 'release_date', $RELEASE_DATE);
        update_post_meta($new_order_id, 'deposit_placed', $timestamp);
        update_post_meta($new_order_id, 'days', $data['length']);
        update_post_meta($new_order_id, 'amount', $data['amount']);

        $province = Province::make($data['province_id']);
        $province->getDeposits();//refresh
        $province->update('money', $province->getMoney() - $data['amount']);
        $province->update('total_deposits', $province->getDepositNum());
        return self::make($new_order_id);
    }

    public function deposited($format=false) {
        $n = round($this->get('amount'));
        return ($format ? Format::money($n) : $n);
    }
    public function availableAmount($format=false) {
        $n = 0;
        if($this->unlocked()) {
            if($this->timeLeft() <= 0) $n = $this->finalAmount();
            else {
                $province = Province::make($this->get('province_id'));
                $bank_level = $province->getResearches('bank_management')['level'];
                $n = $this->deposited() * ($bank_level >= 2 ? Settings::get('bank_management_'.$bank_level.'_withdraw') : 1);
            }
        }
        return ($format ? Format::money(round($n)) : round($n));
    }
    public function finalAmount($format=false) {
        $province = Province::make($this->get('province_id'));
        $length = round($this->get('days'));
        $rate = $province->getBankInterestRate($length);
        $incl_interest = round( $this->deposited() * pow(1+($rate/100), $length) );
        return ($format ? Format::money($incl_interest) : $incl_interest);
    }

    public function getReleaseDate($format=false) {
        $n = $this->get('release_date');
        return ($format ? Format::date($n) : $n);
    }

    public function timeLeft($format=false) {
        $diff = intval($this->get('release_date')) - current_time('timestamp');
        return ($format ? Format::time_diff($diff) : $diff);
    }

    public function unlocked() {
        if($this->timeLeft() <= 0) return true;
        $province = Province::make($this->get('province_id'));
        $bank_level = $province->getResearches('bank_management')['level'];
        if($bank_level >= 2 && $this->get('deposit_placed')+Settings::get('bank_management_2_time') <= current_time('timestamp')) {
            return true;
        }
        return false;
    }

    public function used() {
        return $this->get('status') == 'trash';
    }

    public function end() {
        $province = Province::make($this->get('province_id'));
wtf($this->availableAmount());
        /*$province->update('money', $province->getMoney() + $this->availableAmount());
        wp_trash_post($this->get('id'));
        $province->getDeposits();//refresh
        $province->update('total_deposits', $province->getDepositNum());*/
    }
}