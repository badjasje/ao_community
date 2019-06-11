<?php /*

// Util
Util/Hooks {
    on($action, $callback)
    trigger($action, $args..)
}
Util/PhpObject {}
Util/DbObject extends PhpObject {}
Util/PostObject extends DbObject {} // WP-post, we could someday use our own tables for huge performance improvement

// User
User/User extends DbObject {} // WP-user, we could someday use our own tables for huge performance improvement
User/CurrentUser extends User {}
User/Province extends User? {} // if we ever can create multiple provinces per account
User/UserMedal extends PostObject //post type = medal

// Bank
Bank/Bank extends PhpObject {
    var $rates;
}
Bank/BankAccount extends PhpObject // we might create multiple bankaccounts per province or user someday
Bank/BankDeposit extends PostObject {}

// Market
Market/Market extends PhpObject {
    buy() //
    sell() // units, missiles, sats
}
Market/Order extends PostObject {
    create()
    cancel()
}
Market/MissileOrder extends Order
Market/UnitOrder extends Order
Market/SatelliteOrder extends Order

// Clan
Clan/Clan extends PostObject {
    getAvatar(), getDisplayName(), getHeaderImage()
    getMembers()
}
Clan/ClanWar extends PostObject
Clan/ClanBonus extends PhpObject
Clan/ClanAward extends PostObject

// Attacks
Attack/Attack
Attack/EmpMissileAttack
Attack/EmpSatteliteAttack
Attack/MissileAttack
Attack/SaboteurAttack
Attack/SatteliteAttack
Attack/SniperAttack
Attack/SpyAttack
Attack/ThiefAttack
Attack/UnitAttack

// Fixed Game Data
Data/Satellites extends PhpObject
Data/Units extends PhpObject
Data/Buildings extends PhpObject
Data/Missiles extends PhpObject
Data/Bonusses extends PhpObject
Data/Researches extends PhpObject
Data/StartBonusses extends PhpObject
Data/Trophies extends PhpObject

// Generic
Generic/Translation
Generic/StringHelper

// Other
Event extends PostObject // different type of events?
Message extends PostObject
Research extends PostObject
SpyReport extends PostObject
EMP extends PostObject
// New
Trophy extends PostObject

*/