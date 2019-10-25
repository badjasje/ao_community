<?php
class Research extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);

        if(!empty($this->post_author)) {
            $this->setPropertiesFromArray(array_merge(
                Researches::get($this->post_content),
                array('province_id' => intval($this->post_author), 'key'=>$this->post_content, 'end_time' => intval($this->post_title))
            ));
        }
    }

    public static function create($province_id, $research_key) {
        $province = Province::make($province_id);
        if($province->get('id')) {
            $new_research = $province->getResearches($research_key);
            if($new_research) {
                $endTime = current_time('timestamp') + ($new_research['duration']*60*60);
                $args = array('post_title' => $endTime, 'post_status' => 'publish', 'post_content' => $research_key, 'post_type' => 'research', 'post_author' => $province_id);
                $new_research_id = wp_insert_post($args);
                $province->update('research_in_progress', $research_key);
            }
        }
    }

    public function timeLeft($format=false) {
        return ($format ? Format::time_diff(intval($this->get('end_time'))) : intval($this->get('end_time')) - current_time('timestamp'));
    }

    public function stop() { // maybe we want to stop a current research in the future?
        //$this->get('province_id')
    }

    // Used by research-cronjob and devfunds ajax call
    public function end() {
        // Research is done via user-meta and seperate wp-posts
        $province = Province::make($this->get('province_id'));
        $current_level = intval($province->get('level_'.$this->get('key')));
        $province->update('research_in_progress', 0);
        $province->update('level_'.$this->get('key'), min( ($current_level+1), $this->get('maxlevel')));
        wp_trash_post($this->get('ID'));
        Hooks::trigger('set_province_research', null, $this->get('key'), $province);

        // If a research is queued, we start it here
        $queued_research = $province->get('queued_research'); // returns research key
        if (!empty($queued_research) && $research = Researches::get($queued_research)) {
            $time = $research['duration'];
            $new_research_id = wp_insert_post(array(
                'post_title' => current_time('timestamp') + ($time*60*60), 'post_status' => 'publish',
                'post_content' => $queued_research, 'post_type' => 'research', 'post_author' => $province->get('id')
            ));
            $province->update('research_in_progress', $queued_research);
            $province->update('queued_research', 0);
        }
    }
}