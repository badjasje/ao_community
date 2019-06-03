<?php /*

// Generic
Collection { // glorified array

}
/*Events { //rename?
    on($action, $callback)
    trigger($action, $args..)
}* /
PhpObject {
    setPropertiesFromArray()
    static make()
}
DbObject extends PhpObject {
    //$table?
    add()
    update()
    delete()
    getAll() // where-clause
}
PostObject extends DbObject { // WP-post, we could someday use our own tables for huge performance improvement
    var $params // 
    get_post_meta()
    getAll() // using get_posts(args)
}

// User
User/User extends DbObject {
    var $params; // user data like avatar
    get_user_meta()
    update_user_meta()
    getXP(), getAvatar(), getDisplayName(), getUsername(), getEmail()
}
User/Current extends User {
    // Login, get and logout
    exploreLand(), sellLand()
}
User/Province extends User { //if we ever can create multiple provinces per account
    var $params; // province data like buildings, units, etc
    get_user_meta()
    update_user_meta()
    getDisplayName(),
    getClan(), invite(), kick(), isFellowClanMember(),
    getSatellites(), getResearch(), getBuildings(), getUnits()
    getMedals(), getTrophies(), 
    getMorale(), getTurns(), getNw(), getMorale(), getPool(), getSatMorale(), getLand(), getFreeLand(), getPower()
    calculateNw(), calculatePower(), calculateFreeLand()
    kill(), reset(), isDead(), isProtected()
    attack(), spy()
}
example: Province::make(2768)->getNw(); -> returns int(50000);
example: Province::make(2768)->getNw(true); -> returns string('$ 50 000');

// Bank
Bank/Bank extends PhpObject {
    var $rates;
}
Bank/BankAccount extends PhpObject // we might create multiple bankaccounts per province or user someday
Bank/Deposit extends PostObject {}

// Market
Market extends PhpObject {
    buy() // 
    sell() // units, missiles, sats
}
Market/Order extends PostObject {
    create()
    cancel()
}
Market/Missile extends Order
Market/Unit extends Order
Market/Satellite extends Order

// Clan
Clan/Clan extends PostObject {
    getAvatar(), getDisplayName(), getHeaderImage()
    getMembers()
}
Clan/War extends PostObject
Clan/Bonus extends PhpObject

// Attack
Attack/Attack
Attack/EmpMissile
Attack/EmpSattelite
Attack/Missile
Attack/Saboteur
Attack/Sattelite
Attack/Sniper
Attack/Spy
Attack/Thief
Attack/Unit

// Fixed Game Data
Satellites extends PhpObject 
Units extends PhpObject
Buildings extends PhpObject
Missiles extends PhpObject
Bonusses extends PhpObject
Researches extends PhpObject
StartBonusses extends PhpObject
Trophies extends PhpObject


// Other
Event extends PostObject // different type of events?
Message extends PostObject
Research extends PostObject

SpyReport extends PostObject
ClanAward extends PostObject
Medal extends PostObject
EMP extends PostObject
// New
Trophy extends PostObject

*/