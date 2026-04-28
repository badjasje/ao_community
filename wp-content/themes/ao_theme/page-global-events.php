<?php
/**
 * Template Name: Global events
 */

$user = CurrentUser::make();
$user->update('new_global_events', 0);
$events = $user->getEvents('global');

require_once('page-events.php');
