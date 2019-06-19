<?php
class BankAccount extends PhpObject {

    var $deposits = array();

    public function __construct($props=null) {
        if(is_numeric($props)) {
            $posts = get_posts(array('posts_per_page' => -1, 'author' => $props, 'post_type' => 'deposit'));
            foreach($posts as $post) {
                $this->deposits[$post->ID] = Deposit::make($post);
            }
            parent::__construct(array('id' => $props, 'number' => '')); // @todo: a bankaccount number :-)
        }
    }

    public function getDeposits() {
        return $this->deposits;
    }

    public function getDepositNum() {
        return count($this->deposits);
    }
}
