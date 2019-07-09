<?php /*
// Current objects in AO
// with an * isn't done yet, or might be discarded

// Util
Util/DataObject {}                  // High level parent class for all fixed game data
Util/DbObject extends PhpObject {}  // A database object, could be used for own database tables per object someday
Util/Format extends PhpObject {}    // Type formatting like money, networth and dates
Util/Hooks extends PhpObject {      // Event system so at some point we can make everything more modulair
    on($action, $callback)
    trigger($action, $args..)
}
Util/PhpObject {}                   // An object with some easy to use functions (set,get)
Util/PostObject extends DbObject {} // WP-post, we could someday use our own tables for huge performance improvement
Util/Request {}                     // The current called page with post and get variables

// User
User/CurrentUser extends User {}    // The Current User for this request, logs in and out
User/User extends DbObject {}       // User entity, this is purely a login, person, has messages. Nothing gameplay related
User/Province extends User? {}      // Most used entity. Has land, units, buildings, etc. Attacks and defends.
// A user could also have an "outpost", a "research station", or a second province on another planet.
User/Bonus extends PostObject {}    // Wp-post type, a clan bonus a user can activate
*User/UserMedal extends PostObject  // wp-post type = medal


// Bank
*Bank/Bank extends PhpObject {}     // Has rates?? Can open an accounts? Loans?
Bank/BankAccount extends PhpObject  // We might create multiple bankaccounts per province or user someday
Bank/Deposit extends PostObject {}  // A wp-post type. Has a limited time

// Market
Market/Market extends PhpObject {}  // Is open or closed. Fluctuating prices?
Market/Order extends PostObject {}  // Wp-post type that starts and ends
// We might want to create objects for each type of order (when modulair)

// Research
Research/Research extends PostObject// Wp-post type that starts and ends
// We might want to create objects for each type of research (when modulair)

// Clan
Clan/Clan extends PostObject {}     // Wp-post type with provinces, networth and data
*Clan/ClanWar extends PostObject
*Clan/ClanAward extends PostObject

// Attacks
*Attack/Attack extends PostObject   // Wp-post type
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
// instead of using require(some_array), we use static objects
// Buildings::get() or Buildings::get('powerplant') for generic building data
// $province->getBuildings('powerplant') to get the actual data of a province
// (as it's life might be larger because of research)
Data/Buildings extends DataObject
Data/Missiles extends DataObject
Data/Researches extends DataObject
Data/Satellites extends DataObject
Data/Settings extends DataObject    // Balancing is very important, here we can easily change the "nw range" for instance
Data/StartBonuses extends DataObject
Data/Units extends DataObject
*Data/Bonusses extends DataObject
*Data/Trophies extends DataObject

// Round
Round/Round extends DataObject  // Start- and Endtime, type (dev,test) and status (pause, live)

// Other (todo)
*Event extends PostObject       // Locals, globals etc. We might create
*Message extends PostObject     // Could be in User/Message?
*SpyReport extends PostObject
*EMP extends PostObject
*Trophy extends PostObject      // Could be in User/Trophy?
*News?
*Forum?
*/