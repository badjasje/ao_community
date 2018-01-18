<?php

/** -- Generated using following SQL-statement --

  SELECT DISTINCT
  CONCAT("const _", replace( upper(meta_key), "-", "_"), " ="),
  CONCAT('"', meta_key, '";')
  FROM `23zx_usermeta`
  WHERE NOT meta_key LIKE '\_%'

 * */
class Usermeta {

    const _NICKNAME = "nickname";
    const _FIRST_NAME = "first_name";
    const _LAST_NAME = "last_name";
    const _DESCRIPTION = "description";
    const _RICH_EDITING = "rich_editing";
    const _COMMENT_SHORTCUTS = "comment_shortcuts";
    const _ADMIN_COLOR = "admin_color";
    const _USE_SSL = "use_ssl";
    const _SHOW_ADMIN_BAR_FRONT = "show_admin_bar_front";
    const _23ZX_CAPABILITIES = "23zx_capabilities";
    const _23ZX_USER_LEVEL = "23zx_user_level";
    const _DISMISSED_WP_POINTERS = "dismissed_wp_pointers";
    const _SHOW_WELCOME_PANEL = "show_welcome_panel";
    const _23ZX_DASHBOARD_QUICK_PRESS_LAST_POST_ID = "23zx_dashboard_quick_press_last_post_id";
    const _NAV_MENU_RECENTLY_EDITED = "nav_menu_recently_edited";
    const _MANAGENAV_MENUSCOLUMNSHIDDEN = "managenav-menuscolumnshidden";
    const _METABOXHIDDEN_NAV_MENUS = "metaboxhidden_nav-menus";
    const _CLOSEDPOSTBOXES_NAV_MENUS = "closedpostboxes_nav-menus";
    const _23ZX_USER_SETTINGS = "23zx_user-settings";
    const _23ZX_USER_SETTINGS_TIME = "23zx_user-settings-time";
    const _TGMPA_DISMISSED_NOTICE_TGMPA = "tgmpa_dismissed_notice_tgmpa";
    const _CLOSEDPOSTBOXES_PAGE = "closedpostboxes_page";
    const _METABOXHIDDEN_PAGE = "metaboxhidden_page";
    const _USER_META_IMAGE = "user_meta_image";
    const _USER_META_FACEBOOK = "user_meta_facebook";
    const _USER_META_TWITTER = "user_meta_twitter";
    const _USER_META_GOOGLEPLUS = "user_meta_googleplus";
    const _CLOSEDPOSTBOXES_LISTING = "closedpostboxes_listing";
    const _METABOXHIDDEN_LISTING = "metaboxhidden_listing";
    const _USER_META_DRIBBBLE = "user_meta_dribbble";
    const _MANAGEEDIT_SHOP_ORDERCOLUMNSHIDDEN = "manageedit-shop_ordercolumnshidden";
    const _WPORG_FAVORITES = "wporg_favorites";
    const _F22_RAPTOR_OWNED = "f22_raptor_owned";
    const _MONEY = "money";
    const _TURNS = "turns";
    const _NETWORTH = "networth";
    const _F22_RAPTORS_ORDERED = "f22_raptors_ordered";
    const _F22_RAPTORS_OWNED = "f22_raptors_owned";
    const _RAH66_COMMANCHES_OWNED = "rah66_commanches_owned";
    const _RAH66_COMMANCHES_ORDERED = "rah66_commanches_ordered";
    const _B2_BOMBER_ORDERED = "b2_bomber_ordered";
    const _B2_BOMBER_OWNED = "b2_bomber_owned";
    const _SEAHAWK_OWNED = "seahawk_owned";
    const _SEAHAWK_ORDERED = "seahawk_ordered";
    const _JSF_OWNED = "jsf_owned";
    const _JSF_ORDERED = "jsf_ordered";
    const _HUMVEE_OWNED = "humvee_owned";
    const _HUMVEE_ORDERED = "humvee_ordered";
    const _ABRAHAM_OWNED = "abraham_owned";
    const _ABRAHAM_ORDERED = "abraham_ordered";
    const _ARTILLERY_ORDERED = "artillery_ordered";
    const _ARTILLERY_OWNED = "artillery_owned";
    const _201 = "201";
    const _1 = "1";
    const _LAND = "land";
    const _POWER = "power";
    const _TYPHOON_OWNED = "typhoon_owned";
    const _TYPHOON_ORDERED = "typhoon_ordered";
    const _SAM_OWNED = "sam_owned";
    const _SAM_ORDERED = "sam_ordered";
    const _M70MLRS_OWNED = "m70mlrs_owned";
    const _M70MLRS_ORDERED = "m70mlrs_ordered";
    const _PARATROOPER_OWNED = "paratrooper_owned";
    const _PARATROOPER_ORDERED = "paratrooper_ordered";
    const _GRENADE_OWNED = "grenade_owned";
    const _GRENADE_ORDERED = "grenade_ordered";
    const _NAVY_OWNED = "navy_owned";
    const _NAVY_ORDERED = "navy_ordered";
    const _ROCKET_OWNED = "rocket_owned";
    const _ROCKET_ORDERED = "rocket_ordered";
    const _ARMOURED_OWNED = "armoured_owned";
    const _ARMOURED_ORDERED = "armoured_ordered";
    const _BATTLESHIP_OWNED = "battleship_owned";
    const _BATTLESHIP_ORDERED = "battleship_ordered";
    const _STEALTH_OWNED = "stealth_owned";
    const _STEALTH_ORDERED = "stealth_ordered";
    const _SUBMARINE_OWNED = "submarine_owned";
    const _SUBMARINE_ORDERED = "submarine_ordered";
    const _CRUISER_OWNED = "cruiser_owned";
    const _CRUISER_ORDERED = "cruiser_ordered";
    const _DESTROYER_OWNED = "destroyer_owned";
    const _DESTROYER_ORDERED = "destroyer_ordered";
    const _SILO = "silo";
    const _COMMAND_CENTRE = "command_centre";
    const _SHIPYARD = "shipyard";
    const _AIRFIELD = "airfield";
    const _WARFACTORY = "warfactory";
    const _BARACKS = "baracks";
    const _POWERPLANT = "powerplant";
    const _ADVANCEDPOWERPLANT = "advancedpowerplant";
    const _TORPEDOLAUNCHER = "torpedolauncher";
    const _SAMSITE = "samsite";
    const _MISSILETURRET = "missileturret";
    const _MACHINEGUNTURRET = "machinegunturret";
    const _ANTIMISSILE = "antimissile";
    const _BUILTLAND = "builtland";
    const _PLAYERNAME = "playername";
    const _MORALE = "morale";
    const _NUKE_OWNED = "nuke_owned";
    const _NUKE_ORDERED = "nuke_ordered";
    const _CHEMICAL_OWNED = "chemical_owned";
    const _CHEMICAL_ORDERED = "chemical_ordered";
    const _BIO_OWNED = "bio_owned";
    const _BIO_ORDERED = "bio_ordered";
    const _MOAB_OWNED = "moab_owned";
    const _MOAB_ORDERED = "moab_ordered";
    const _STATUS = "status";
    const _MISSILE_MORALE = "missile_morale";
    const _NUKE = "nuke";
    const _THIEF_OWNED = "thief_owned";
    const _THIEF_ORDERED = "thief_ordered";
    const _NEW_EVENTS = "new_events";
    const _SPY_OWNED = "spy_owned";
    const _SPY_ORDERED = "spy_ordered";
    const _SPYPLANE_OWNED = "spyplane_owned";
    const _SPYPLANE_ORDERED = "spyplane_ordered";
    const _CLAN_ID_USER = "clan_id_user";
    const _CLAN_MESSAGE = "clan_message";
    const _EDIT_USER_MESSAGE_PER_PAGE = "edit_user_message_per_page";
    const _CLAN_ID = "clan_id";
    const _ASGAROSFORUM_LASTVISIT = "asgarosforum_lastvisit";
    const _NUKE_PROTECTION_TIMESTAMP = "nuke_protection_timestamp";
    const _DEFAULT_PASSWORD_NAG = "default_password_nag";
    const _SESSION_TOKENS = "session_tokens";
    const _USER_CLAN_POINTS = "user_clan_points";
    const _EDIT_PAGE_PER_PAGE = "edit_page_per_page";
    const _B2_BOMBER = "b2_bomber";
    const _CHEMICAL = "chemical";
    const _EXPLORED_TODAY = "explored_today";
    const _LAND_SOLD_TODAY = "land_sold_today";
    const _SOLD_LAND_TODAY = "sold_land_today";
    const _TOTAL_DEPOSITS = "total_deposits";
    const _NEW_MESSAGES = "new_messages";
    const _MOAB = "moab";
    const _EDIT_SUB_USER_MESSAGE_PER_PAGE = "edit_sub_user_message_per_page";
    const _RESEARCH_IN_PROGRESS = "research_in_progress";
    const _LEVEL_MONEY_PRODUCTION = "level_money_production";
    const _LEVEL_MISSILE_ACCURACY = "level_missile_accuracy";
    const _LEVEL_SATTELITE_CONSTRUCTION = "level_sattelite_construction";
    const _LEVEL_SHIPPING_TIME = "level_shipping_time";
    const _LEVEL_MARKET_DISCOUNT = "level_market_discount";
    const _LEVEL_THIEVING_EFFECTIVENESS = "level_thieving_effectiveness";
    const _LEVEL_ENGINEERING_EFFECTIVENESS = "level_engineering_effectiveness";
    const _LEVEL_BANK_MANAGEMENT = "level_bank_management";
    const _LEVEL_POWERPLANT_EFFICIENCY = "level_powerplant_efficiency";
    const _RESEARCH_TIMESTAMP = "research_timestamp";
    const _EDIT_EVENT_LOCAL_PER_PAGE = "edit_event_local_per_page";
    const _LAST_ONLINE = "last_online";
    const _WPPB_PMS_CROSS_PROMO_DISMISS_NOTIFICATION = "wppb_pms_cross_promo_dismiss_notification";
    const _QUEUED_RESEARCH = "queued_research";
    const _LEVEL_ = "level_";
    const _MORALE_POOL = "morale_pool";
    const _SAT_IN_PROGRESS = "sat_in_progress";
    const _SAT_OWNED = "sat_owned";
    const _USER_COUNTRY = "user_country";
    const _SAT_ENDLIFE = "sat_endlife";
    const _LEVEL_SATELLITE_CONSTRUCTION = "level_satellite_construction";
    const _LASER_ORDERED = "laser_ordered";
    const _SAT_MORALE = "sat_morale";
    const _NEW_GLOBAL_EVENTS = "new_global_events";
    const _WPSEO_IGNORE_TOUR = "wpseo_ignore_tour";
    const _WPSEO_TITLE = "wpseo_title";
    const _WPSEO_METADESC = "wpseo_metadesc";
    const _WPSEO_METAKEY = "wpseo_metakey";
    const _WPSEO_EXCLUDEAUTHORSITEMAP = "wpseo_excludeauthorsitemap";
    const _GOOGLEPLUS = "googleplus";
    const _TWITTER = "twitter";
    const _FACEBOOK = "facebook";
    const _POINTS_POSITION = "points_position";
    const _NETWORTH_POSITION = "networth_position";
    const _TGMPA_DISMISSED_NOTICE = "tgmpa_dismissed_notice";
    const _FB_PROFILE_PICTURE = "fb_profile_picture";
    const _FB_USER_ACCESS_TOKEN = "fb_user_access_token";
    const _WPSEO_CONTENT_ANALYSIS_DISABLE = "wpseo_content_analysis_disable";
    const _WPSEO_KEYWORD_ANALYSIS_DISABLE = "wpseo_keyword_analysis_disable";
    const _ATTACKS_MADE = "attacks_made";
    const _BUILDINGS_BUILT = "buildings_built";
    const _UNITS_BUILT_TURNS = "units_built_turns";
    const _MORALE_LOST = "morale_lost";
    const _UNITS_SOLD = "units_sold";
    const _HIGHEST_LAND = "highest_land";
    const _HIGHEST_NETWORTH = "highest_networth";
    const _ATTACKS_RECEIVED = "attacks_received";
    const _MONEY_GAINED_COMBAT = "money_gained_combat";
    const _LAND_GAINED_COMBAT = "land_gained_combat";
    const _MONEY_LOST_COMBAT = "money_lost_combat";
    const _LAND_LOST_COMBAT = "land_lost_combat";
    const _UNITS_KILLED = "units_killed";
    const _NW_DAMAGE_ATTACKS = "nw_damage_attacks";
    const _BUILDINGS_KILLED = "buildings_killed";
    const _NW_DAMAGE_LOST = "nw_damage_lost";
    const _UNITS_LOST = "units_lost";
    const _BUILDINGS_LOST = "buildings_lost";
    const _SUCCESFUL_ATTACKS = "succesful_attacks";
    const _ATTACKS_LOST = "attacks_lost";
    const _TURNS_LOST = "turns_lost";
    const _DRAGON_OWNED = "dragon_owned";
    const _DRAGON_ORDERED = "dragon_ordered";
    const _KILLS_MADE = "kills_made";
    const _MISSILES_LAUNCHED = "missiles_launched";
    const _MISSILES_HIT = "missiles_hit";
    const _NW_DAMAGE_MISSILES = "nw_damage_missiles";
    const _THIEVING_ATTEMPTS = "thieving_attempts";
    const _SUCCESFUL_ATTEMPTS = "succesful_attempts";
    const _MONEY_GAINED_THIEVING = "money_gained_thieving";
    const _TIMES_KILLED = "times_killed";
    const _MISSILES_RECEIVED = "missiles_received";
    const _MISSILES_HIT_REC = "missiles_hit_rec";
    const _NW_DAMAGE_MISSILES_REC = "nw_damage_missiles_rec";
    const _ATTEMPTS_RECEIVED = "attempts_received";
    const _SUCCESFUL_ATTEMPTS_REC = "succesful_attempts_rec";
    const _MONEY_LOST_THIEVING = "money_lost_thieving";
    const _UNITS_ORDERED = "units_ordered";
    const _MORALE_USED = "morale_used";
    const _ASGAROSFORUM_UNREAD_CLEARED = "asgarosforum_unread_cleared";
    const _ASGAROSFORUM_SUBSCRIPTION_TOPIC = "asgarosforum_subscription_topic";
    const _LAST_ACTIVE_TIME = "last_active_time";
    const _NAME_CHANGE_COUNTER = "name_change_counter";
    const _MANAGEEDIT_EVENT_LOCALCOLUMNSHIDDEN = "manageedit-event_localcolumnshidden";
    const _CLOSEDPOSTBOXES_EVENT_LOCAL = "closedpostboxes_event_local";
    const _METABOXHIDDEN_EVENT_LOCAL = "metaboxhidden_event_local";
    const _MANAGEEDIT_DEPOSITCOLUMNSHIDDEN = "manageedit-depositcolumnshidden";
    const _EDIT_DEPOSIT_PER_PAGE = "edit_deposit_per_page";
    const _EDIT_MARKET_ORDER_PER_PAGE = "edit_market_order_per_page";
    const _NEW_CLAN_TIMESTAMP = "new_clan_timestamp";
    const _AID_SENT_TODAY = "aid_sent_today";
    const _AVATAR_USER = "avatar_user";
    const _SNIPER_ORDERED = "sniper_ordered";
    const _SNIPER_OWNED = "sniper_owned";
    const _M270_ROCKET_OWNED = "m270_rocket_owned";
    const _M270_ROCKET_ORDERED = "m270_rocket_ordered";
    const _RIFLE_OWNED = "rifle_owned";
    const _RIFLE_ORDERED = "rifle_ordered";
    const _SPARROW_OWNED = "sparrow_owned";
    const _SPARROW_ORDERED = "sparrow_ordered";
    const _LAST_ATTACKED = "last_attacked";
    const _STARTING_BONUS = "starting_bonus";
    const _CARRIER_OWNED = "carrier_owned";
    const _FRIGATE_OWNED = "frigate_owned";
    const _FRIGATE_ORDERED = "frigate_ordered";
    const _CARRIER_ORDERED = "carrier_ordered";
    const _RESET_STATUS = "reset_status";
    const _CLOSEDPOSTBOXES_AWARD = "closedpostboxes_award";
    const _METABOXHIDDEN_AWARD = "metaboxhidden_award";
    const _CLOSEDPOSTBOXES_MEDAL = "closedpostboxes_medal";
    const _METABOXHIDDEN_MEDAL = "metaboxhidden_medal";
    const _ASGAROSFORUM_UNREAD_EXCLUDE = "asgarosforum_unread_exclude";
    const _EDIT_ASGAROSFORUM_CATEGORY_PER_PAGE = "edit_asgarosforum-category_per_page";
    const _SPECIAL_SOLD_TODAY = "special_sold_today";
    const _UAL_PLUGIN_UPGRADE_NOTICE = "ual_plugin_upgrade_notice";
    const _UAL_PER_PAGE = "ual_per_page";
    const _APC_OWNED = "apc_owned";
    const _APC_ORDERED = "apc_ordered";
    const _FLAMETHROWER_OWNED = "flamethrower_owned";
    const _FLAMETHROWER_ORDERED = "flamethrower_ordered";
    const _23ZX_YOAST_NOTIFICATIONS = "23zx_yoast_notifications";
    const _STEALTH_SAT_STATUS = "stealth_sat_status";
    const _SAT_NW = "sat_nw";
    const _RESEARCH_NW = "research_nw";
    const _BUILDING_NW = "building_nw";
    const _UNIT_NW = "unit_nw";
    const _LAND_NW = "land_nw";
    const _MISSILE_NW = "missile_nw";
    const _COMMUNITY_EVENTS_LOCATION = "community-events-location";
    const _USER_LOCK = "user_lock";
    const _MORALE_LOCK = "morale_lock";

    public $userId = null;
    public $data = null;

    // constructor
    public function __construct($userId) {
        $this->userId = $userId;
        $this->data = get_user_meta($userId, null, true);
    }

    private function getUserLock() {
        return get_user_meta($this->userId, self::_USER_LOCK, true);
    }

    private function setUserLock($value) {
        update_user_meta($this->userId, self::_USER_LOCK, $value);
    }

    public function tryLock() {
        if ($this->getUserLock() != 0) {
            return false;
        } else {
            $this->setUserLock(1);
            return true;
        }
    }

    public function unlock() {
        $this->setUserLock(0);
    }

    public function genCode() {
        foreach ($this->data as $k => $v) {
            $str = ucwords($k, '_-');
            $str = str_replace("-", "", $str);
            $str = str_replace("_", "", $str);

            if (!array_key_exists(ltrim($k, '_'), $this->data) || substr($key, 0, 1) != '_') {

                //if ($v!=null && is_array($v)) {

                $o = "public function get" . $str . "(){";
                $o = $o . "return \$this->data['" . $k . "'][0]; }\n";

                $o = $o . "public function set" . $str . "(\$value){";
                $o = $o . "update_user_meta(\$this->userId, '$k', \$value); }";

                print_r($o . "\n");
            }
        }
    }

    // code below is generated using genCode()

    public function getNickname() {
        return $this->data['nickname'][0];
    }

    public function setNickname($value) {
        update_user_meta($this->userId, 'nickname', $value);
    }

    public function getFirstName() {
        return $this->data['first_name'][0];
    }

    public function setFirstName($value) {
        update_user_meta($this->userId, 'first_name', $value);
    }

    public function getLastName() {
        return $this->data['last_name'][0];
    }

    public function setLastName($value) {
        update_user_meta($this->userId, 'last_name', $value);
    }

    public function getDescription() {
        return $this->data['description'][0];
    }

    public function setDescription($value) {
        update_user_meta($this->userId, 'description', $value);
    }

    public function getRichEditing() {
        return $this->data['rich_editing'][0];
    }

    public function setRichEditing($value) {
        update_user_meta($this->userId, 'rich_editing', $value);
    }

    public function getCommentShortcuts() {
        return $this->data['comment_shortcuts'][0];
    }

    public function setCommentShortcuts($value) {
        update_user_meta($this->userId, 'comment_shortcuts', $value);
    }

    public function getAdminColor() {
        return $this->data['admin_color'][0];
    }

    public function setAdminColor($value) {
        update_user_meta($this->userId, 'admin_color', $value);
    }

    public function getUseSsl() {
        return $this->data['use_ssl'][0];
    }

    public function setUseSsl($value) {
        update_user_meta($this->userId, 'use_ssl', $value);
    }

    public function getShowAdminBarFront() {
        return $this->data['show_admin_bar_front'][0];
    }

    public function setShowAdminBarFront($value) {
        update_user_meta($this->userId, 'show_admin_bar_front', $value);
    }

    public function get23zxCapabilities() {
        return $this->data['23zx_capabilities'][0];
    }

    public function set23zxCapabilities($value) {
        update_user_meta($this->userId, '23zx_capabilities', $value);
    }

    public function get23zxUserLevel() {
        return $this->data['23zx_user_level'][0];
    }

    public function set23zxUserLevel($value) {
        update_user_meta($this->userId, '23zx_user_level', $value);
    }

    public function getDismissedWpPointers() {
        return $this->data['dismissed_wp_pointers'][0];
    }

    public function setDismissedWpPointers($value) {
        update_user_meta($this->userId, 'dismissed_wp_pointers', $value);
    }

    public function getShowWelcomePanel() {
        return $this->data['show_welcome_panel'][0];
    }

    public function setShowWelcomePanel($value) {
        update_user_meta($this->userId, 'show_welcome_panel', $value);
    }

    public function get23zxDashboardQuickPressLastPostId() {
        return $this->data['23zx_dashboard_quick_press_last_post_id'][0];
    }

    public function set23zxDashboardQuickPressLastPostId($value) {
        update_user_meta($this->userId, '23zx_dashboard_quick_press_last_post_id', $value);
    }

    public function getNavMenuRecentlyEdited() {
        return $this->data['nav_menu_recently_edited'][0];
    }

    public function setNavMenuRecentlyEdited($value) {
        update_user_meta($this->userId, 'nav_menu_recently_edited', $value);
    }

    public function getManagenavMenuscolumnshidden() {
        return $this->data['managenav-menuscolumnshidden'][0];
    }

    public function setManagenavMenuscolumnshidden($value) {
        update_user_meta($this->userId, 'managenav-menuscolumnshidden', $value);
    }

    public function getMetaboxhiddenNavMenus() {
        return $this->data['metaboxhidden_nav-menus'][0];
    }

    public function setMetaboxhiddenNavMenus($value) {
        update_user_meta($this->userId, 'metaboxhidden_nav-menus', $value);
    }

    public function getClosedpostboxesNavMenus() {
        return $this->data['closedpostboxes_nav-menus'][0];
    }

    public function setClosedpostboxesNavMenus($value) {
        update_user_meta($this->userId, 'closedpostboxes_nav-menus', $value);
    }

    public function get23zxUserSettings() {
        return $this->data['23zx_user-settings'][0];
    }

    public function set23zxUserSettings($value) {
        update_user_meta($this->userId, '23zx_user-settings', $value);
    }

    public function get23zxUserSettingsTime() {
        return $this->data['23zx_user-settings-time'][0];
    }

    public function set23zxUserSettingsTime($value) {
        update_user_meta($this->userId, '23zx_user-settings-time', $value);
    }

    public function getTgmpaDismissedNoticeTgmpa() {
        return $this->data['tgmpa_dismissed_notice_tgmpa'][0];
    }

    public function setTgmpaDismissedNoticeTgmpa($value) {
        update_user_meta($this->userId, 'tgmpa_dismissed_notice_tgmpa', $value);
    }

    public function getClosedpostboxesPage() {
        return $this->data['closedpostboxes_page'][0];
    }

    public function setClosedpostboxesPage($value) {
        update_user_meta($this->userId, 'closedpostboxes_page', $value);
    }

    public function getMetaboxhiddenPage() {
        return $this->data['metaboxhidden_page'][0];
    }

    public function setMetaboxhiddenPage($value) {
        update_user_meta($this->userId, 'metaboxhidden_page', $value);
    }

    public function getUserMetaImage() {
        return $this->data['user_meta_image'][0];
    }

    public function setUserMetaImage($value) {
        update_user_meta($this->userId, 'user_meta_image', $value);
    }

    public function getUserMetaFacebook() {
        return $this->data['user_meta_facebook'][0];
    }

    public function setUserMetaFacebook($value) {
        update_user_meta($this->userId, 'user_meta_facebook', $value);
    }

    public function getUserMetaTwitter() {
        return $this->data['user_meta_twitter'][0];
    }

    public function setUserMetaTwitter($value) {
        update_user_meta($this->userId, 'user_meta_twitter', $value);
    }

    public function getUserMetaGoogleplus() {
        return $this->data['user_meta_googleplus'][0];
    }

    public function setUserMetaGoogleplus($value) {
        update_user_meta($this->userId, 'user_meta_googleplus', $value);
    }

    public function getClosedpostboxesListing() {
        return $this->data['closedpostboxes_listing'][0];
    }

    public function setClosedpostboxesListing($value) {
        update_user_meta($this->userId, 'closedpostboxes_listing', $value);
    }

    public function getMetaboxhiddenListing() {
        return $this->data['metaboxhidden_listing'][0];
    }

    public function setMetaboxhiddenListing($value) {
        update_user_meta($this->userId, 'metaboxhidden_listing', $value);
    }

    public function getManageeditShopOrdercolumnshidden() {
        return $this->data['manageedit-shop_ordercolumnshidden'][0];
    }

    public function setManageeditShopOrdercolumnshidden($value) {
        update_user_meta($this->userId, 'manageedit-shop_ordercolumnshidden', $value);
    }

    public function getWporgFavorites() {
        return $this->data['wporg_favorites'][0];
    }

    public function setWporgFavorites($value) {
        update_user_meta($this->userId, 'wporg_favorites', $value);
    }

    public function getF22RaptorOwned() {
        return $this->data['f22_raptor_owned'][0];
    }

    public function setF22RaptorOwned($value) {
        update_user_meta($this->userId, 'f22_raptor_owned', $value);
    }

    public function getMoney() {
        return $this->data['money'][0];
    }

    public function setMoney($value) {
        update_user_meta($this->userId, 'money', $value);
    }

    public function getTurns() {
        return $this->data['turns'][0];
    }

    public function setTurns($value) {
        update_user_meta($this->userId, 'turns', $value);
    }

    public function getNetworth() {
        return $this->data['networth'][0];
    }

    public function setNetworth($value) {
        update_user_meta($this->userId, 'networth', $value);
    }

    public function getF22RaptorsOrdered() {
        return $this->data['f22_raptors_ordered'][0];
    }

    public function setF22RaptorsOrdered($value) {
        update_user_meta($this->userId, 'f22_raptors_ordered', $value);
    }

    public function getF22RaptorsOwned() {
        return $this->data['f22_raptors_owned'][0];
    }

    public function setF22RaptorsOwned($value) {
        update_user_meta($this->userId, 'f22_raptors_owned', $value);
    }

    public function getRah66CommanchesOwned() {
        return $this->data['rah66_commanches_owned'][0];
    }

    public function setRah66CommanchesOwned($value) {
        update_user_meta($this->userId, 'rah66_commanches_owned', $value);
    }

    public function getRah66CommanchesOrdered() {
        return $this->data['rah66_commanches_ordered'][0];
    }

    public function setRah66CommanchesOrdered($value) {
        update_user_meta($this->userId, 'rah66_commanches_ordered', $value);
    }

    public function getOrdered() {
        return $this->data['_ordered'][0];
    }

    public function setOrdered($value) {
        update_user_meta($this->userId, '_ordered', $value);
    }

    public function getOwned() {
        return $this->data['_owned'][0];
    }

    public function setOwned($value) {
        update_user_meta($this->userId, '_owned', $value);
    }

    public function getB2BomberOrdered() {
        return $this->data['b2_bomber_ordered'][0];
    }

    public function setB2BomberOrdered($value) {
        update_user_meta($this->userId, 'b2_bomber_ordered', $value);
    }

    public function getB2BomberOwned() {
        return $this->data['b2_bomber_owned'][0];
    }

    public function setB2BomberOwned($value) {
        update_user_meta($this->userId, 'b2_bomber_owned', $value);
    }

    public function getSeahawkOwned() {
        return $this->data['seahawk_owned'][0];
    }

    public function setSeahawkOwned($value) {
        update_user_meta($this->userId, 'seahawk_owned', $value);
    }

    public function getSeahawkOrdered() {
        return $this->data['seahawk_ordered'][0];
    }

    public function setSeahawkOrdered($value) {
        update_user_meta($this->userId, 'seahawk_ordered', $value);
    }

    public function getJsfOwned() {
        return $this->data['jsf_owned'][0];
    }

    public function setJsfOwned($value) {
        update_user_meta($this->userId, 'jsf_owned', $value);
    }

    public function getJsfOrdered() {
        return $this->data['jsf_ordered'][0];
    }

    public function setJsfOrdered($value) {
        update_user_meta($this->userId, 'jsf_ordered', $value);
    }

    public function getHumveeOwned() {
        return $this->data['humvee_owned'][0];
    }

    public function setHumveeOwned($value) {
        update_user_meta($this->userId, 'humvee_owned', $value);
    }

    public function getHumveeOrdered() {
        return $this->data['humvee_ordered'][0];
    }

    public function setHumveeOrdered($value) {
        update_user_meta($this->userId, 'humvee_ordered', $value);
    }

    public function getAbrahamOwned() {
        return $this->data['abraham_owned'][0];
    }

    public function setAbrahamOwned($value) {
        update_user_meta($this->userId, 'abraham_owned', $value);
    }

    public function getAbrahamOrdered() {
        return $this->data['abraham_ordered'][0];
    }

    public function setAbrahamOrdered($value) {
        update_user_meta($this->userId, 'abraham_ordered', $value);
    }

    public function getArtilleryOrdered() {
        return $this->data['artillery_ordered'][0];
    }

    public function setArtilleryOrdered($value) {
        update_user_meta($this->userId, 'artillery_ordered', $value);
    }

    public function getArtilleryOwned() {
        return $this->data['artillery_owned'][0];
    }

    public function setArtilleryOwned($value) {
        update_user_meta($this->userId, 'artillery_owned', $value);
    }

    public function get201() {
        return $this->data['201'][0];
    }

    public function set201($value) {
        update_user_meta($this->userId, '201', $value);
    }

    public function getLand() {
        return $this->data['land'][0];
    }

    public function setLand($value) {
        update_user_meta($this->userId, 'land', $value);
    }

    public function getPower() {
        return $this->data['power'][0];
    }

    public function setPower($value) {
        update_user_meta($this->userId, 'power', $value);
    }

    public function getTyphoonOwned() {
        return $this->data['typhoon_owned'][0];
    }

    public function setTyphoonOwned($value) {
        update_user_meta($this->userId, 'typhoon_owned', $value);
    }

    public function getTyphoonOrdered() {
        return $this->data['typhoon_ordered'][0];
    }

    public function setTyphoonOrdered($value) {
        update_user_meta($this->userId, 'typhoon_ordered', $value);
    }

    public function getSamOwned() {
        return $this->data['sam_owned'][0];
    }

    public function setSamOwned($value) {
        update_user_meta($this->userId, 'sam_owned', $value);
    }

    public function getSamOrdered() {
        return $this->data['sam_ordered'][0];
    }

    public function setSamOrdered($value) {
        update_user_meta($this->userId, 'sam_ordered', $value);
    }

    public function getM70mlrsOwned() {
        return $this->data['m70mlrs_owned'][0];
    }

    public function setM70mlrsOwned($value) {
        update_user_meta($this->userId, 'm70mlrs_owned', $value);
    }

    public function getM70mlrsOrdered() {
        return $this->data['m70mlrs_ordered'][0];
    }

    public function setM70mlrsOrdered($value) {
        update_user_meta($this->userId, 'm70mlrs_ordered', $value);
    }

    public function getParatrooperOwned() {
        return $this->data['paratrooper_owned'][0];
    }

    public function setParatrooperOwned($value) {
        update_user_meta($this->userId, 'paratrooper_owned', $value);
    }

    public function getParatrooperOrdered() {
        return $this->data['paratrooper_ordered'][0];
    }

    public function setParatrooperOrdered($value) {
        update_user_meta($this->userId, 'paratrooper_ordered', $value);
    }

    public function getGrenadeOwned() {
        return $this->data['grenade_owned'][0];
    }

    public function setGrenadeOwned($value) {
        update_user_meta($this->userId, 'grenade_owned', $value);
    }

    public function getGrenadeOrdered() {
        return $this->data['grenade_ordered'][0];
    }

    public function setGrenadeOrdered($value) {
        update_user_meta($this->userId, 'grenade_ordered', $value);
    }

    public function getNavyOwned() {
        return $this->data['navy_owned'][0];
    }

    public function setNavyOwned($value) {
        update_user_meta($this->userId, 'navy_owned', $value);
    }

    public function getNavyOrdered() {
        return $this->data['navy_ordered'][0];
    }

    public function setNavyOrdered($value) {
        update_user_meta($this->userId, 'navy_ordered', $value);
    }

    public function getRocketOwned() {
        return $this->data['rocket_owned'][0];
    }

    public function setRocketOwned($value) {
        update_user_meta($this->userId, 'rocket_owned', $value);
    }

    public function getRocketOrdered() {
        return $this->data['rocket_ordered'][0];
    }

    public function setRocketOrdered($value) {
        update_user_meta($this->userId, 'rocket_ordered', $value);
    }

    public function getArmouredOwned() {
        return $this->data['armoured_owned'][0];
    }

    public function setArmouredOwned($value) {
        update_user_meta($this->userId, 'armoured_owned', $value);
    }

    public function getArmouredOrdered() {
        return $this->data['armoured_ordered'][0];
    }

    public function setArmouredOrdered($value) {
        update_user_meta($this->userId, 'armoured_ordered', $value);
    }

    public function getBattleshipOwned() {
        return $this->data['battleship_owned'][0];
    }

    public function setBattleshipOwned($value) {
        update_user_meta($this->userId, 'battleship_owned', $value);
    }

    public function getBattleshipOrdered() {
        return $this->data['battleship_ordered'][0];
    }

    public function setBattleshipOrdered($value) {
        update_user_meta($this->userId, 'battleship_ordered', $value);
    }

    public function getStealthOwned() {
        return $this->data['stealth_owned'][0];
    }

    public function setStealthOwned($value) {
        update_user_meta($this->userId, 'stealth_owned', $value);
    }

    public function getStealthOrdered() {
        return $this->data['stealth_ordered'][0];
    }

    public function setStealthOrdered($value) {
        update_user_meta($this->userId, 'stealth_ordered', $value);
    }

    public function getSubmarineOwned() {
        return $this->data['submarine_owned'][0];
    }

    public function setSubmarineOwned($value) {
        update_user_meta($this->userId, 'submarine_owned', $value);
    }

    public function getSubmarineOrdered() {
        return $this->data['submarine_ordered'][0];
    }

    public function setSubmarineOrdered($value) {
        update_user_meta($this->userId, 'submarine_ordered', $value);
    }

    public function getCruiserOwned() {
        return $this->data['cruiser_owned'][0];
    }

    public function setCruiserOwned($value) {
        update_user_meta($this->userId, 'cruiser_owned', $value);
    }

    public function getCruiserOrdered() {
        return $this->data['cruiser_ordered'][0];
    }

    public function setCruiserOrdered($value) {
        update_user_meta($this->userId, 'cruiser_ordered', $value);
    }

    public function getDestroyerOwned() {
        return $this->data['destroyer_owned'][0];
    }

    public function setDestroyerOwned($value) {
        update_user_meta($this->userId, 'destroyer_owned', $value);
    }

    public function getDestroyerOrdered() {
        return $this->data['destroyer_ordered'][0];
    }

    public function setDestroyerOrdered($value) {
        update_user_meta($this->userId, 'destroyer_ordered', $value);
    }

    public function getSilo() {
        return $this->data['silo'][0];
    }

    public function setSilo($value) {
        update_user_meta($this->userId, 'silo', $value);
    }

    public function getCommandCentre() {
        return $this->data['command_centre'][0];
    }

    public function setCommandCentre($value) {
        update_user_meta($this->userId, 'command_centre', $value);
    }

    public function getShipyard() {
        return $this->data['shipyard'][0];
    }

    public function setShipyard($value) {
        update_user_meta($this->userId, 'shipyard', $value);
    }

    public function getAirfield() {
        return $this->data['airfield'][0];
    }

    public function setAirfield($value) {
        update_user_meta($this->userId, 'airfield', $value);
    }

    public function getWarfactory() {
        return $this->data['warfactory'][0];
    }

    public function setWarfactory($value) {
        update_user_meta($this->userId, 'warfactory', $value);
    }

    public function getBaracks() {
        return $this->data['baracks'][0];
    }

    public function setBaracks($value) {
        update_user_meta($this->userId, 'baracks', $value);
    }

    public function getPowerplant() {
        return $this->data['powerplant'][0];
    }

    public function setPowerplant($value) {
        update_user_meta($this->userId, 'powerplant', $value);
    }

    public function getAdvancedpowerplant() {
        return $this->data['advancedpowerplant'][0];
    }

    public function setAdvancedpowerplant($value) {
        update_user_meta($this->userId, 'advancedpowerplant', $value);
    }

    public function getTorpedolauncher() {
        return $this->data['torpedolauncher'][0];
    }

    public function setTorpedolauncher($value) {
        update_user_meta($this->userId, 'torpedolauncher', $value);
    }

    public function getSamsite() {
        return $this->data['samsite'][0];
    }

    public function setSamsite($value) {
        update_user_meta($this->userId, 'samsite', $value);
    }

    public function getMissileturret() {
        return $this->data['missileturret'][0];
    }

    public function setMissileturret($value) {
        update_user_meta($this->userId, 'missileturret', $value);
    }

    public function getMachinegunturret() {
        return $this->data['machinegunturret'][0];
    }

    public function setMachinegunturret($value) {
        update_user_meta($this->userId, 'machinegunturret', $value);
    }

    public function getAntimissile() {
        return $this->data['antimissile'][0];
    }

    public function setAntimissile($value) {
        update_user_meta($this->userId, 'antimissile', $value);
    }

    public function getBuiltland() {
        return $this->data['builtland'][0];
    }

    public function setBuiltland($value) {
        update_user_meta($this->userId, 'builtland', $value);
    }

    public function getPlayername() {
        return $this->data['playername'][0];
    }

    public function setPlayername($value) {
        update_user_meta($this->userId, 'playername', $value);
    }

    public function getMorale() {
        return $this->data['morale'][0];
    }

    public function setMorale($value) {
        update_user_meta($this->userId, 'morale', $value);
    }

    public function getNukeOwned() {
        return $this->data['nuke_owned'][0];
    }

    public function setNukeOwned($value) {
        update_user_meta($this->userId, 'nuke_owned', $value);
    }

    public function getNukeOrdered() {
        return $this->data['nuke_ordered'][0];
    }

    public function setNukeOrdered($value) {
        update_user_meta($this->userId, 'nuke_ordered', $value);
    }

    public function getChemicalOwned() {
        return $this->data['chemical_owned'][0];
    }

    public function setChemicalOwned($value) {
        update_user_meta($this->userId, 'chemical_owned', $value);
    }

    public function getChemicalOrdered() {
        return $this->data['chemical_ordered'][0];
    }

    public function setChemicalOrdered($value) {
        update_user_meta($this->userId, 'chemical_ordered', $value);
    }

    public function getBioOwned() {
        return $this->data['bio_owned'][0];
    }

    public function setBioOwned($value) {
        update_user_meta($this->userId, 'bio_owned', $value);
    }

    public function getBioOrdered() {
        return $this->data['bio_ordered'][0];
    }

    public function setBioOrdered($value) {
        update_user_meta($this->userId, 'bio_ordered', $value);
    }

    public function getMoabOwned() {
        return $this->data['moab_owned'][0];
    }

    public function setMoabOwned($value) {
        update_user_meta($this->userId, 'moab_owned', $value);
    }

    public function getMoabOrdered() {
        return $this->data['moab_ordered'][0];
    }

    public function setMoabOrdered($value) {
        update_user_meta($this->userId, 'moab_ordered', $value);
    }

    public function getStatus() {
        return $this->data['status'][0];
    }

    public function setStatus($value) {
        update_user_meta($this->userId, 'status', $value);
    }

    public function getMissileMorale() {
        return $this->data['missile_morale'][0];
    }

    public function setMissileMorale($value) {
        update_user_meta($this->userId, 'missile_morale', $value);
    }

    public function getNuke() {
        return $this->data['nuke'][0];
    }

    public function setNuke($value) {
        update_user_meta($this->userId, 'nuke', $value);
    }

    public function getThiefOwned() {
        return $this->data['thief_owned'][0];
    }

    public function setThiefOwned($value) {
        update_user_meta($this->userId, 'thief_owned', $value);
    }

    public function getThiefOrdered() {
        return $this->data['thief_ordered'][0];
    }

    public function setThiefOrdered($value) {
        update_user_meta($this->userId, 'thief_ordered', $value);
    }

    public function getNewEvents() {
        return $this->data['new_events'][0];
    }

    public function setNewEvents($value) {
        update_user_meta($this->userId, 'new_events', $value);
    }

    public function getSpyOwned() {
        return $this->data['spy_owned'][0];
    }

    public function setSpyOwned($value) {
        update_user_meta($this->userId, 'spy_owned', $value);
    }

    public function getSpyOrdered() {
        return $this->data['spy_ordered'][0];
    }

    public function setSpyOrdered($value) {
        update_user_meta($this->userId, 'spy_ordered', $value);
    }

    public function getSpyplaneOwned() {
        return $this->data['spyplane_owned'][0];
    }

    public function setSpyplaneOwned($value) {
        update_user_meta($this->userId, 'spyplane_owned', $value);
    }

    public function getSpyplaneOrdered() {
        return $this->data['spyplane_ordered'][0];
    }

    public function setSpyplaneOrdered($value) {
        update_user_meta($this->userId, 'spyplane_ordered', $value);
    }

    public function getClanIdUser() {
        return $this->data['clan_id_user'][0];
    }

    public function setClanIdUser($value) {
        update_user_meta($this->userId, 'clan_id_user', $value);
    }

    public function getClanMessage() {
        return $this->data['clan_message'][0];
    }

    public function setClanMessage($value) {
        update_user_meta($this->userId, 'clan_message', $value);
    }

    public function getEditUserMessagePerPage() {
        return $this->data['edit_user_message_per_page'][0];
    }

    public function setEditUserMessagePerPage($value) {
        update_user_meta($this->userId, 'edit_user_message_per_page', $value);
    }

    public function getAsgarosforumLastvisit() {
        return $this->data['asgarosforum_lastvisit'][0];
    }

    public function setAsgarosforumLastvisit($value) {
        update_user_meta($this->userId, 'asgarosforum_lastvisit', $value);
    }

    public function getNukeProtectionTimestamp() {
        return $this->data['nuke_protection_timestamp'][0];
    }

    public function setNukeProtectionTimestamp($value) {
        update_user_meta($this->userId, 'nuke_protection_timestamp', $value);
    }

    public function getUserClanPoints() {
        return $this->data['user_clan_points'][0];
    }

    public function setUserClanPoints($value) {
        update_user_meta($this->userId, 'user_clan_points', $value);
    }

    public function getEditPagePerPage() {
        return $this->data['edit_page_per_page'][0];
    }

    public function setEditPagePerPage($value) {
        update_user_meta($this->userId, 'edit_page_per_page', $value);
    }

    public function getB2Bomber() {
        return $this->data['b2_bomber'][0];
    }

    public function setB2Bomber($value) {
        update_user_meta($this->userId, 'b2_bomber', $value);
    }

    public function getChemical() {
        return $this->data['chemical'][0];
    }

    public function setChemical($value) {
        update_user_meta($this->userId, 'chemical', $value);
    }

    public function getExploredToday() {
        return $this->data['explored_today'][0];
    }

    public function setExploredToday($value) {
        update_user_meta($this->userId, 'explored_today', $value);
    }

    public function getLandSoldToday() {
        return $this->data['land_sold_today'][0];
    }

    public function setLandSoldToday($value) {
        update_user_meta($this->userId, 'land_sold_today', $value);
    }

    public function getSoldLandToday() {
        return $this->data['sold_land_today'][0];
    }

    public function setSoldLandToday($value) {
        update_user_meta($this->userId, 'sold_land_today', $value);
    }

    public function getTotalDeposits() {
        return $this->data['total_deposits'][0];
    }

    public function setTotalDeposits($value) {
        update_user_meta($this->userId, 'total_deposits', $value);
    }

    public function getNewMessages() {
        return $this->data['new_messages'][0];
    }

    public function setNewMessages($value) {
        update_user_meta($this->userId, 'new_messages', $value);
    }

    public function getEditSubUserMessagePerPage() {
        return $this->data['edit_sub_user_message_per_page'][0];
    }

    public function setEditSubUserMessagePerPage($value) {
        update_user_meta($this->userId, 'edit_sub_user_message_per_page', $value);
    }

    public function getResearchInProgress() {
        return $this->data['research_in_progress'][0];
    }

    public function setResearchInProgress($value) {
        update_user_meta($this->userId, 'research_in_progress', $value);
    }

    public function getLevelMoneyProduction() {
        return $this->data['level_money_production'][0];
    }

    public function setLevelMoneyProduction($value) {
        update_user_meta($this->userId, 'level_money_production', $value);
    }

    public function getLevelMissileAccuracy() {
        return $this->data['level_missile_accuracy'][0];
    }

    public function setLevelMissileAccuracy($value) {
        update_user_meta($this->userId, 'level_missile_accuracy', $value);
    }

    public function getLevelSatteliteConstruction() {
        return $this->data['level_sattelite_construction'][0];
    }

    public function setLevelSatteliteConstruction($value) {
        update_user_meta($this->userId, 'level_sattelite_construction', $value);
    }

    public function getLevelShippingTime() {
        return $this->data['level_shipping_time'][0];
    }

    public function setLevelShippingTime($value) {
        update_user_meta($this->userId, 'level_shipping_time', $value);
    }

    public function getLevelMarketDiscount() {
        return $this->data['level_market_discount'][0];
    }

    public function setLevelMarketDiscount($value) {
        update_user_meta($this->userId, 'level_market_discount', $value);
    }

    public function getLevelThievingEffectiveness() {
        return $this->data['level_thieving_effectiveness'][0];
    }

    public function setLevelThievingEffectiveness($value) {
        update_user_meta($this->userId, 'level_thieving_effectiveness', $value);
    }

    public function getLevelEngineeringEffectiveness() {
        return $this->data['level_engineering_effectiveness'][0];
    }

    public function setLevelEngineeringEffectiveness($value) {
        update_user_meta($this->userId, 'level_engineering_effectiveness', $value);
    }

    public function getLevelBankManagement() {
        return $this->data['level_bank_management'][0];
    }

    public function setLevelBankManagement($value) {
        update_user_meta($this->userId, 'level_bank_management', $value);
    }

    public function getLevelPowerplantEfficiency() {
        return $this->data['level_powerplant_efficiency'][0];
    }

    public function setLevelPowerplantEfficiency($value) {
        update_user_meta($this->userId, 'level_powerplant_efficiency', $value);
    }

    public function getResearchTimestamp() {
        return $this->data['research_timestamp'][0];
    }

    public function setResearchTimestamp($value) {
        update_user_meta($this->userId, 'research_timestamp', $value);
    }

    public function getEditEventLocalPerPage() {
        return $this->data['edit_event_local_per_page'][0];
    }

    public function setEditEventLocalPerPage($value) {
        update_user_meta($this->userId, 'edit_event_local_per_page', $value);
    }

    public function getLastOnline() {
        return $this->data['last_online'][0];
    }

    public function setLastOnline($value) {
        update_user_meta($this->userId, 'last_online', $value);
    }

    public function getWppbPmsCrossPromoDismissNotification() {
        return $this->data['wppb_pms_cross_promo_dismiss_notification'][0];
    }

    public function setWppbPmsCrossPromoDismissNotification($value) {
        update_user_meta($this->userId, 'wppb_pms_cross_promo_dismiss_notification', $value);
    }

    public function getQueuedResearch() {
        return $this->data['queued_research'][0];
    }

    public function setQueuedResearch($value) {
        update_user_meta($this->userId, 'queued_research', $value);
    }

    public function getMoralePool() {
        return $this->data['morale_pool'][0];
    }

    public function setMoralePool($value) {
        update_user_meta($this->userId, 'morale_pool', $value);
    }

    public function getSatInProgress() {
        return $this->data['sat_in_progress'][0];
    }

    public function setSatInProgress($value) {
        update_user_meta($this->userId, 'sat_in_progress', $value);
    }

    public function getSatOwned() {
        return $this->data['sat_owned'][0];
    }

    public function setSatOwned($value) {
        update_user_meta($this->userId, 'sat_owned', $value);
    }

    public function getUserCountry() {
        return $this->data['user_country'][0];
    }

    public function setUserCountry($value) {
        update_user_meta($this->userId, 'user_country', $value);
    }

    public function getSatEndlife() {
        return $this->data['sat_endlife'][0];
    }

    public function setSatEndlife($value) {
        update_user_meta($this->userId, 'sat_endlife', $value);
    }

    public function getLevelSatelliteConstruction() {
        return $this->data['level_satellite_construction'][0];
    }

    public function setLevelSatelliteConstruction($value) {
        update_user_meta($this->userId, 'level_satellite_construction', $value);
    }

    public function getLaserOrdered() {
        return $this->data['laser_ordered'][0];
    }

    public function setLaserOrdered($value) {
        update_user_meta($this->userId, 'laser_ordered', $value);
    }

    public function getSatMorale() {
        return $this->data['sat_morale'][0];
    }

    public function setSatMorale($value) {
        update_user_meta($this->userId, 'sat_morale', $value);
    }

    public function getNewGlobalEvents() {
        return $this->data['new_global_events'][0];
    }

    public function setNewGlobalEvents($value) {
        update_user_meta($this->userId, 'new_global_events', $value);
    }

    public function getWpseoIgnoreTour() {
        return $this->data['wpseo_ignore_tour'][0];
    }

    public function setWpseoIgnoreTour($value) {
        update_user_meta($this->userId, 'wpseo_ignore_tour', $value);
    }

    public function getYoastWpseoProfileUpdated() {
        return $this->data['_yoast_wpseo_profile_updated'][0];
    }

    public function setYoastWpseoProfileUpdated($value) {
        update_user_meta($this->userId, '_yoast_wpseo_profile_updated', $value);
    }

    public function getWpseoTitle() {
        return $this->data['wpseo_title'][0];
    }

    public function setWpseoTitle($value) {
        update_user_meta($this->userId, 'wpseo_title', $value);
    }

    public function getWpseoMetadesc() {
        return $this->data['wpseo_metadesc'][0];
    }

    public function setWpseoMetadesc($value) {
        update_user_meta($this->userId, 'wpseo_metadesc', $value);
    }

    public function getWpseoMetakey() {
        return $this->data['wpseo_metakey'][0];
    }

    public function setWpseoMetakey($value) {
        update_user_meta($this->userId, 'wpseo_metakey', $value);
    }

    public function getWpseoExcludeauthorsitemap() {
        return $this->data['wpseo_excludeauthorsitemap'][0];
    }

    public function setWpseoExcludeauthorsitemap($value) {
        update_user_meta($this->userId, 'wpseo_excludeauthorsitemap', $value);
    }

    public function getGoogleplus() {
        return $this->data['googleplus'][0];
    }

    public function setGoogleplus($value) {
        update_user_meta($this->userId, 'googleplus', $value);
    }

    public function getTwitter() {
        return $this->data['twitter'][0];
    }

    public function setTwitter($value) {
        update_user_meta($this->userId, 'twitter', $value);
    }

    public function getFacebook() {
        return $this->data['facebook'][0];
    }

    public function setFacebook($value) {
        update_user_meta($this->userId, 'facebook', $value);
    }

    public function getPointsPosition() {
        return $this->data['points_position'][0];
    }

    public function setPointsPosition($value) {
        update_user_meta($this->userId, 'points_position', $value);
    }

    public function getNetworthPosition() {
        return $this->data['networth_position'][0];
    }

    public function setNetworthPosition($value) {
        update_user_meta($this->userId, 'networth_position', $value);
    }

    public function getTgmpaDismissedNotice() {
        return $this->data['tgmpa_dismissed_notice'][0];
    }

    public function setTgmpaDismissedNotice($value) {
        update_user_meta($this->userId, 'tgmpa_dismissed_notice', $value);
    }

    public function getWpseoContentAnalysisDisable() {
        return $this->data['wpseo_content_analysis_disable'][0];
    }

    public function setWpseoContentAnalysisDisable($value) {
        update_user_meta($this->userId, 'wpseo_content_analysis_disable', $value);
    }

    public function getWpseoKeywordAnalysisDisable() {
        return $this->data['wpseo_keyword_analysis_disable'][0];
    }

    public function setWpseoKeywordAnalysisDisable($value) {
        update_user_meta($this->userId, 'wpseo_keyword_analysis_disable', $value);
    }

    public function getBuildingsBuilt() {
        return $this->data['buildings_built'][0];
    }

    public function setBuildingsBuilt($value) {
        update_user_meta($this->userId, 'buildings_built', $value);
    }

    public function getUnitsBuiltTurns() {
        return $this->data['units_built_turns'][0];
    }

    public function setUnitsBuiltTurns($value) {
        update_user_meta($this->userId, 'units_built_turns', $value);
    }

    public function getMoraleLost() {
        return $this->data['morale_lost'][0];
    }

    public function setMoraleLost($value) {
        update_user_meta($this->userId, 'morale_lost', $value);
    }

    public function getUnitsSold() {
        return $this->data['units_sold'][0];
    }

    public function setUnitsSold($value) {
        update_user_meta($this->userId, 'units_sold', $value);
    }

    public function getHighestLand() {
        return $this->data['highest_land'][0];
    }

    public function setHighestLand($value) {
        update_user_meta($this->userId, 'highest_land', $value);
    }

    public function getHighestNetworth() {
        return $this->data['highest_networth'][0];
    }

    public function setHighestNetworth($value) {
        update_user_meta($this->userId, 'highest_networth', $value);
    }

    public function getAttacksMade() {
        return $this->data['attacks_made'][0];
    }

    public function setAttacksMade($value) {
        update_user_meta($this->userId, 'attacks_made', $value);
    }

    public function getMoneyGainedCombat() {
        return $this->data['money_gained_combat'][0];
    }

    public function setMoneyGainedCombat($value) {
        update_user_meta($this->userId, 'money_gained_combat', $value);
    }

    public function getLandGainedCombat() {
        return $this->data['land_gained_combat'][0];
    }

    public function setLandGainedCombat($value) {
        update_user_meta($this->userId, 'land_gained_combat', $value);
    }

    public function getUnitsKilled() {
        return $this->data['units_killed'][0];
    }

    public function setUnitsKilled($value) {
        update_user_meta($this->userId, 'units_killed', $value);
    }

    public function getNwDamageAttacks() {
        return $this->data['nw_damage_attacks'][0];
    }

    public function setNwDamageAttacks($value) {
        update_user_meta($this->userId, 'nw_damage_attacks', $value);
    }

    public function getBuildingsKilled() {
        return $this->data['buildings_killed'][0];
    }

    public function setBuildingsKilled($value) {
        update_user_meta($this->userId, 'buildings_killed', $value);
    }

    public function getSuccesfulAttacks() {
        return $this->data['succesful_attacks'][0];
    }

    public function setSuccesfulAttacks($value) {
        update_user_meta($this->userId, 'succesful_attacks', $value);
    }

    public function getTurnsLost() {
        return $this->data['turns_lost'][0];
    }

    public function setTurnsLost($value) {
        update_user_meta($this->userId, 'turns_lost', $value);
    }

    public function getDragonOwned() {
        return $this->data['dragon_owned'][0];
    }

    public function setDragonOwned($value) {
        update_user_meta($this->userId, 'dragon_owned', $value);
    }

    public function getDragonOrdered() {
        return $this->data['dragon_ordered'][0];
    }

    public function setDragonOrdered($value) {
        update_user_meta($this->userId, 'dragon_ordered', $value);
    }

    public function getKillsMade() {
        return $this->data['kills_made'][0];
    }

    public function setKillsMade($value) {
        update_user_meta($this->userId, 'kills_made', $value);
    }

    public function getMissilesLaunched() {
        return $this->data['missiles_launched'][0];
    }

    public function setMissilesLaunched($value) {
        update_user_meta($this->userId, 'missiles_launched', $value);
    }

    public function getMissilesHit() {
        return $this->data['missiles_hit'][0];
    }

    public function setMissilesHit($value) {
        update_user_meta($this->userId, 'missiles_hit', $value);
    }

    public function getNwDamageMissiles() {
        return $this->data['nw_damage_missiles'][0];
    }

    public function setNwDamageMissiles($value) {
        update_user_meta($this->userId, 'nw_damage_missiles', $value);
    }

    public function getThievingAttempts() {
        return $this->data['thieving_attempts'][0];
    }

    public function setThievingAttempts($value) {
        update_user_meta($this->userId, 'thieving_attempts', $value);
    }

    public function getSuccesfulAttempts() {
        return $this->data['succesful_attempts'][0];
    }

    public function setSuccesfulAttempts($value) {
        update_user_meta($this->userId, 'succesful_attempts', $value);
    }

    public function getMoneyGainedThieving() {
        return $this->data['money_gained_thieving'][0];
    }

    public function setMoneyGainedThieving($value) {
        update_user_meta($this->userId, 'money_gained_thieving', $value);
    }

    public function getAttacksReceived() {
        return $this->data['attacks_received'][0];
    }

    public function setAttacksReceived($value) {
        update_user_meta($this->userId, 'attacks_received', $value);
    }

    public function getAttacksLost() {
        return $this->data['attacks_lost'][0];
    }

    public function setAttacksLost($value) {
        update_user_meta($this->userId, 'attacks_lost', $value);
    }

    public function getNwDamageLost() {
        return $this->data['nw_damage_lost'][0];
    }

    public function setNwDamageLost($value) {
        update_user_meta($this->userId, 'nw_damage_lost', $value);
    }

    public function getUnitsLost() {
        return $this->data['units_lost'][0];
    }

    public function setUnitsLost($value) {
        update_user_meta($this->userId, 'units_lost', $value);
    }

    public function getBuildingsLost() {
        return $this->data['buildings_lost'][0];
    }

    public function setBuildingsLost($value) {
        update_user_meta($this->userId, 'buildings_lost', $value);
    }

    public function getMoneyLostCombat() {
        return $this->data['money_lost_combat'][0];
    }

    public function setMoneyLostCombat($value) {
        update_user_meta($this->userId, 'money_lost_combat', $value);
    }

    public function getLandLostCombat() {
        return $this->data['land_lost_combat'][0];
    }

    public function setLandLostCombat($value) {
        update_user_meta($this->userId, 'land_lost_combat', $value);
    }

    public function getTimesKilled() {
        return $this->data['times_killed'][0];
    }

    public function setTimesKilled($value) {
        update_user_meta($this->userId, 'times_killed', $value);
    }

    public function getMissilesReceived() {
        return $this->data['missiles_received'][0];
    }

    public function setMissilesReceived($value) {
        update_user_meta($this->userId, 'missiles_received', $value);
    }

    public function getMissilesHitRec() {
        return $this->data['missiles_hit_rec'][0];
    }

    public function setMissilesHitRec($value) {
        update_user_meta($this->userId, 'missiles_hit_rec', $value);
    }

    public function getNwDamageMissilesRec() {
        return $this->data['nw_damage_missiles_rec'][0];
    }

    public function setNwDamageMissilesRec($value) {
        update_user_meta($this->userId, 'nw_damage_missiles_rec', $value);
    }

    public function getAttemptsReceived() {
        return $this->data['attempts_received'][0];
    }

    public function setAttemptsReceived($value) {
        update_user_meta($this->userId, 'attempts_received', $value);
    }

    public function getSuccesfulAttemptsRec() {
        return $this->data['succesful_attempts_rec'][0];
    }

    public function setSuccesfulAttemptsRec($value) {
        update_user_meta($this->userId, 'succesful_attempts_rec', $value);
    }

    public function getMoneyLostThieving() {
        return $this->data['money_lost_thieving'][0];
    }

    public function setMoneyLostThieving($value) {
        update_user_meta($this->userId, 'money_lost_thieving', $value);
    }

    public function getUnitsOrdered() {
        return $this->data['units_ordered'][0];
    }

    public function setUnitsOrdered($value) {
        update_user_meta($this->userId, 'units_ordered', $value);
    }

    public function getMoraleUsed() {
        return $this->data['morale_used'][0];
    }

    public function setMoraleUsed($value) {
        update_user_meta($this->userId, 'morale_used', $value);
    }

    public function getAsgarosforumUnreadCleared() {
        return $this->data['asgarosforum_unread_cleared'][0];
    }

    public function setAsgarosforumUnreadCleared($value) {
        update_user_meta($this->userId, 'asgarosforum_unread_cleared', $value);
    }

    public function getNameChangeCounter() {
        return $this->data['name_change_counter'][0];
    }

    public function setNameChangeCounter($value) {
        update_user_meta($this->userId, 'name_change_counter', $value);
    }

    public function getManageeditEventLocalcolumnshidden() {
        return $this->data['manageedit-event_localcolumnshidden'][0];
    }

    public function setManageeditEventLocalcolumnshidden($value) {
        update_user_meta($this->userId, 'manageedit-event_localcolumnshidden', $value);
    }

    public function getClosedpostboxesEventLocal() {
        return $this->data['closedpostboxes_event_local'][0];
    }

    public function setClosedpostboxesEventLocal($value) {
        update_user_meta($this->userId, 'closedpostboxes_event_local', $value);
    }

    public function getMetaboxhiddenEventLocal() {
        return $this->data['metaboxhidden_event_local'][0];
    }

    public function setMetaboxhiddenEventLocal($value) {
        update_user_meta($this->userId, 'metaboxhidden_event_local', $value);
    }

    public function getManageeditDepositcolumnshidden() {
        return $this->data['manageedit-depositcolumnshidden'][0];
    }

    public function setManageeditDepositcolumnshidden($value) {
        update_user_meta($this->userId, 'manageedit-depositcolumnshidden', $value);
    }

    public function getEditDepositPerPage() {
        return $this->data['edit_deposit_per_page'][0];
    }

    public function setEditDepositPerPage($value) {
        update_user_meta($this->userId, 'edit_deposit_per_page', $value);
    }

    public function getEditMarketOrderPerPage() {
        return $this->data['edit_market_order_per_page'][0];
    }

    public function setEditMarketOrderPerPage($value) {
        update_user_meta($this->userId, 'edit_market_order_per_page', $value);
    }

    public function getAidSentToday() {
        return $this->data['aid_sent_today'][0];
    }

    public function setAidSentToday($value) {
        update_user_meta($this->userId, 'aid_sent_today', $value);
    }

    public function getNewClanTimestamp() {
        return $this->data['new_clan_timestamp'][0];
    }

    public function setNewClanTimestamp($value) {
        update_user_meta($this->userId, 'new_clan_timestamp', $value);
    }

    public function getAvatarUser() {
        return $this->data['avatar_user'][0];
    }

    public function setAvatarUser($value) {
        update_user_meta($this->userId, 'avatar_user', $value);
    }

    public function getSniperOrdered() {
        return $this->data['sniper_ordered'][0];
    }

    public function setSniperOrdered($value) {
        update_user_meta($this->userId, 'sniper_ordered', $value);
    }

    public function getSniperOwned() {
        return $this->data['sniper_owned'][0];
    }

    public function setSniperOwned($value) {
        update_user_meta($this->userId, 'sniper_owned', $value);
    }

    public function getM270RocketOwned() {
        return $this->data['m270_rocket_owned'][0];
    }

    public function setM270RocketOwned($value) {
        update_user_meta($this->userId, 'm270_rocket_owned', $value);
    }

    public function getM270RocketOrdered() {
        return $this->data['m270_rocket_ordered'][0];
    }

    public function setM270RocketOrdered($value) {
        update_user_meta($this->userId, 'm270_rocket_ordered', $value);
    }

    public function getRifleOwned() {
        return $this->data['rifle_owned'][0];
    }

    public function setRifleOwned($value) {
        update_user_meta($this->userId, 'rifle_owned', $value);
    }

    public function getRifleOrdered() {
        return $this->data['rifle_ordered'][0];
    }

    public function setRifleOrdered($value) {
        update_user_meta($this->userId, 'rifle_ordered', $value);
    }

    public function getSparrowOwned() {
        return $this->data['sparrow_owned'][0];
    }

    public function setSparrowOwned($value) {
        update_user_meta($this->userId, 'sparrow_owned', $value);
    }

    public function getSparrowOrdered() {
        return $this->data['sparrow_ordered'][0];
    }

    public function setSparrowOrdered($value) {
        update_user_meta($this->userId, 'sparrow_ordered', $value);
    }

    public function getLastAttacked() {
        return $this->data['last_attacked'][0];
    }

    public function setLastAttacked($value) {
        update_user_meta($this->userId, 'last_attacked', $value);
    }

    public function getStartingBonus() {
        return $this->data['starting_bonus'][0];
    }

    public function setStartingBonus($value) {
        update_user_meta($this->userId, 'starting_bonus', $value);
    }

    public function getCarrierOwned() {
        return $this->data['carrier_owned'][0];
    }

    public function setCarrierOwned($value) {
        update_user_meta($this->userId, 'carrier_owned', $value);
    }

    public function getFrigateOwned() {
        return $this->data['frigate_owned'][0];
    }

    public function setFrigateOwned($value) {
        update_user_meta($this->userId, 'frigate_owned', $value);
    }

    public function getFrigateOrdered() {
        return $this->data['frigate_ordered'][0];
    }

    public function setFrigateOrdered($value) {
        update_user_meta($this->userId, 'frigate_ordered', $value);
    }

    public function getCarrierOrdered() {
        return $this->data['carrier_ordered'][0];
    }

    public function setCarrierOrdered($value) {
        update_user_meta($this->userId, 'carrier_ordered', $value);
    }

    public function getResetStatus() {
        return $this->data['reset_status'][0];
    }

    public function setResetStatus($value) {
        update_user_meta($this->userId, 'reset_status', $value);
    }

    public function getClosedpostboxesAward() {
        return $this->data['closedpostboxes_award'][0];
    }

    public function setClosedpostboxesAward($value) {
        update_user_meta($this->userId, 'closedpostboxes_award', $value);
    }

    public function getMetaboxhiddenAward() {
        return $this->data['metaboxhidden_award'][0];
    }

    public function setMetaboxhiddenAward($value) {
        update_user_meta($this->userId, 'metaboxhidden_award', $value);
    }

    public function getClosedpostboxesMedal() {
        return $this->data['closedpostboxes_medal'][0];
    }

    public function setClosedpostboxesMedal($value) {
        update_user_meta($this->userId, 'closedpostboxes_medal', $value);
    }

    public function getMetaboxhiddenMedal() {
        return $this->data['metaboxhidden_medal'][0];
    }

    public function setMetaboxhiddenMedal($value) {
        update_user_meta($this->userId, 'metaboxhidden_medal', $value);
    }

    public function getAsgarosforumUnreadExclude() {
        return $this->data['asgarosforum_unread_exclude'][0];
    }

    public function setAsgarosforumUnreadExclude($value) {
        update_user_meta($this->userId, 'asgarosforum_unread_exclude', $value);
    }

    public function getEditAsgarosforumCategoryPerPage() {
        return $this->data['edit_asgarosforum-category_per_page'][0];
    }

    public function setEditAsgarosforumCategoryPerPage($value) {
        update_user_meta($this->userId, 'edit_asgarosforum-category_per_page', $value);
    }

    public function getSpecialSoldToday() {
        return $this->data['special_sold_today'][0];
    }

    public function setSpecialSoldToday($value) {
        update_user_meta($this->userId, 'special_sold_today', $value);
    }

    public function getUalPluginUpgradeNotice() {
        return $this->data['ual_plugin_upgrade_notice'][0];
    }

    public function setUalPluginUpgradeNotice($value) {
        update_user_meta($this->userId, 'ual_plugin_upgrade_notice', $value);
    }

    public function getUalPerPage() {
        return $this->data['ual_per_page'][0];
    }

    public function setUalPerPage($value) {
        update_user_meta($this->userId, 'ual_per_page', $value);
    }

    public function getFlamethrowerOrdered() {
        return $this->data['flamethrower_ordered'][0];
    }

    public function setFlamethrowerOrdered($value) {
        update_user_meta($this->userId, 'flamethrower_ordered', $value);
    }

    public function getFlamethrowerOwned() {
        return $this->data['flamethrower_owned'][0];
    }

    public function setFlamethrowerOwned($value) {
        update_user_meta($this->userId, 'flamethrower_owned', $value);
    }

    public function getApcOwned() {
        return $this->data['apc_owned'][0];
    }

    public function setApcOwned($value) {
        update_user_meta($this->userId, 'apc_owned', $value);
    }

    public function getApcOrdered() {
        return $this->data['apc_ordered'][0];
    }

    public function setApcOrdered($value) {
        update_user_meta($this->userId, 'apc_ordered', $value);
    }

    public function get23zxYoastNotifications() {
        return $this->data['23zx_yoast_notifications'][0];
    }

    public function set23zxYoastNotifications($value) {
        update_user_meta($this->userId, '23zx_yoast_notifications', $value);
    }

    public function getSatNw() {
        return $this->data['sat_nw'][0];
    }

    public function setSatNw($value) {
        update_user_meta($this->userId, 'sat_nw', $value);
    }

    public function getResearchNw() {
        return $this->data['research_nw'][0];
    }

    public function setResearchNw($value) {
        update_user_meta($this->userId, 'research_nw', $value);
    }

    public function getBuildingNw() {
        return $this->data['building_nw'][0];
    }

    public function setBuildingNw($value) {
        update_user_meta($this->userId, 'building_nw', $value);
    }

    public function getUnitNw() {
        return $this->data['unit_nw'][0];
    }

    public function setUnitNw($value) {
        update_user_meta($this->userId, 'unit_nw', $value);
    }

    public function getLandNw() {
        return $this->data['land_nw'][0];
    }

    public function setLandNw($value) {
        update_user_meta($this->userId, 'land_nw', $value);
    }

    public function getMissileNw() {
        return $this->data['missile_nw'][0];
    }

    public function setMissileNw($value) {
        update_user_meta($this->userId, 'missile_nw', $value);
    }

    public function getCommunityEventsLocation() {
        return $this->data['community-events-location'][0];
    }

    public function setCommunityEventsLocation($value) {
        update_user_meta($this->userId, 'community-events-location', $value);
    }

    public function getMoraleLock() {
        return $this->data['morale_lock'][0];
    }

    public function setMoraleLock($value) {
        update_user_meta($this->userId, 'morale_lock', $value);
    }

    public function getSessionTokens() {
        return $this->data['session_tokens'][0];
    }

    public function setSessionTokens($value) {
        update_user_meta($this->userId, 'session_tokens', $value);
    }

}

?>