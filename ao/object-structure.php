<?php /*

// Util
Util/DbObject extends PhpObject {} // A database object, will be used for own database tables per object
Util/Format extends PhpObject {}   // Type formatting like money, networth and dates
Util/Hooks extends PhpObject {
    on($action, $callback)
    trigger($action, $args..)
}
Util/PhpObject {}                   // An object with some easy functions
Util/PostObject extends DbObject {} // WP-post, we could someday use our own tables for huge performance improvement
Util/Request {}                     // The current called page with post and get variables
*Util/Translation

// User
User/User extends DbObject {}       // WP-user, we could someday use our own tables for huge performance improvement
User/CurrentUser extends User {}    // CurrentUser
User/Province extends User? {}      // Most used entity. If we ever can create multiple provinces per account we can do so
*User/UserMedal extends PostObject  // post type = medal

// Bank
*Bank/Bank extends PhpObject {}     // Has rates?? OpenAccount() CloseAccount() Loans?
Bank/BankAccount extends PhpObject  // We might create multiple bankaccounts per province or user someday
Bank/Deposit extends PostObject {}

// Market
Market/Market extends PhpObject {}  // Fluctuating prices?
*Market/Order extends PostObject {  // Entity that starts and ends
    create()
    cancel()
    end()
}
*Market/MissileOrder extends Order
*Market/UnitOrder extends Order
*Market/SatelliteOrder extends Order

// Clan
*Clan/Clan extends PostObject {
    getAvatar(), getDisplayName(), getHeaderImage()
    getMembers()
}
*Clan/ClanWar extends PostObject
*Clan/ClanBonus extends PhpObject
*Clan/ClanAward extends PostObject

// Attacks
*Attack/Attack
*Attack/EmpMissileAttack
*Attack/EmpSatteliteAttack
*Attack/MissileAttack
*Attack/SaboteurAttack
*Attack/SatteliteAttack
*Attack/SniperAttack
*Attack/SpyAttack
*Attack/ThiefAttack
*Attack/UnitAttack

// Fixed Game Data
Data/Buildings extends PhpObject
Data/Missiles extends PhpObject
Data/Researches extends PhpObject
Data/Satellites extends PhpObject
Data/Settings extends PhpObject
Data/Units extends PhpObject
*Data/Bonusses extends PhpObject
*Data/StartBonusses extends PhpObject
*Data/Trophies extends PhpObject

// Other
*Event extends PostObject       // different type of events?
*Message extends PostObject
Research extends PostObject     // Entity that starts and ends
*SpyReport extends PostObject
*EMP extends PostObject
// New
*Trophy extends PostObject

*/