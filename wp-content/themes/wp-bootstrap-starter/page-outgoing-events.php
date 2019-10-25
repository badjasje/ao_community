<?php
/**
 * Template Name: Outgoing events
 */

$user = CurrentUser::make();
$events = $user->getEvents('outgoing');

require_once('page-events.php');
