<?php
/*
	constants for use throughout
/*
/* default income */
$INCOME_TURNS = 1;
$INCOME_MONEY = 15000;
$INCOME_MORALE = 5;

/* attack range multiplier */
$ATTACK_RANGE_MULT = 1.4;
$AVERAGE_DECLARE_NW_ALLOWED = 1.6;

/* morale costs */
$MORALE_MISSILE_TGT_BELOW = 40;
$MORALE_MISSILE_TGT_ABOVE = 35;
$MORALE_ATTACK_TGT_BELOW = 25;
$MORALE_ATTACK_TGT_ABOVE = 20;
$MORALE_THIEF = 5;
$MORALE_SABOTEUR = 30;
$MORALE_SPY = 0;

/* turn costs */
$TURNS_MISSILE = 3;
$TURNS_ATTACK = 3;
$TURNS_THIEF = 2;
$TURNS_SPY = 1;

/* war points multipliers */
$WAR_POINTS_MULT_MUTUAL = 1.0;
$WAR_POINTS_MULT_OUTGOING = 1.0;
$WAR_POINTS_MULT_INCOMING = 0.5;
$WAR_POINTS_MULT_NONE = 0;

/* kill values */
$POINTS_KILL_OUTGOING = 25;
$POINTS_KILL_INCOMING = 25;
$POINTS_KILL_MUTUAL = 50;

/* free resource steal ratios */
$STOLEN_LAND_RATIO = 0.075;
$STOLEN_MONEY_RATIO = 0.02;

/* missile dice rolls */
$MISSILE_HIT_CHANCE = 90;
$MISSILE_DICEROLL_DAMAGE_MIN = 90;
$MISSILE_DICEROLL_DAMAGE_MAX = 110;

/* unit dice rolls */
$UNIT_DICEROLL_DAMAGE_MIN = 95;
$UNIT_DICEROLL_DAMAGE_MAX = 105;

/* resource dice rolls */
$RESOURCE_DICEROLL_MIN = 90;
$RESOURCE_DICEROLL_MAX = 140;

/* useful arrays */
$DEFENSIVE_BUILDINGS = array('torpedolauncher', 'samsite', 'missileturret', 'machinegunturret');
$ALL_TYPES = array('sea', 'air', 'veh', 'inf', 'bld');
$UNIT_TYPES = array('sea', 'air', 'veh', 'inf');
$SPECIAL_UNITS = array('spyplane', 'thief', 'spy','sniper','saboteur');

/* points calculation constants */
$POINTS_NET_WEIGHT = 200;
$POINTS_UNITS_WEIGHT = 100;

/* land per building */
$LAND_PER_BUILDING = 20;

/* research */
$RESEARCH_NW_PER_HOUR = 950;