<?php
/**
 * Template Name: Local events
 */

$user = CurrentUser::make();
$user->update('new_events', 0);
$events = $user->getEvents('incoming');

require_once('page-events.php');
