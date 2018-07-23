<?php
 /*
 * Template Name: Inbox
*/
get_header(); 
global $userId;
global $userData;
$backColor = "45, 67, 81";
update_user_meta($userId,'new_messages',0);
?>

<div class="row pageRow">	
	
<div class="fw-row">
	<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#inbox" href="?tab=inbox">Inbox</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#outbox" href="?tab=outbox">Outbox</a>
	</nav>
</div>
		
		
<div class="tab-content current">
	
	<?php include 'pages/inbox/inbox.php'; ?>
	<?php include 'pages/inbox/outbox.php'; ?>
</div>
		
</div> <!-- end .pageRow -->
<?php
get_footer();