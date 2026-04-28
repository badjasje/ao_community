<?php
// @todo: we might OOP this stuff too
$args = array('posts_per_page' => 5, 'orderby' => 'date', 'order' => 'DESC', 'post_status' => 'publish', 'post_type' => 'post');
$posts = get_posts($args);
global $wpdb;
$topics = $wpdb->get_results("SELECT * FROM 23zx_forum_topics ORDER BY 23zx_forum_topics.id DESC LIMIT 5");

?>
<div class="statusBlock">
	<div class="row statusTotalRow">

		<div class="col-md-6 col-lg-3 statusRow statCol-4">
			<div class="blockHeader">Latest orders</div>
			<? foreach ($province->getOrders() as $order) { ?>
				<div class="row unitRow">
					<div class="col-md-4 celBlock nameBlock sea_heading"><?=$order->title()?></div>
					<div class="col-md-4 celBlock">
						<span class="columnDataLeft">Ordered</span>
						<span class="columnDataRight"><?=$order->amount()?></span>
					</div>
					<div class="col-md-4 celBlock">
						<span class="columnDataLeft">Time left</span>
						<span class="columnDataRight" data-countdown="<?=$order->timeleft()?>"></span>
					</div>
				</div>
			<? } ?>
		</div>

		<div class="col-md-6 col-lg-3 statusRow statCol-3">
			<div class="blockHeader">Latest news</div>
			<? foreach ($posts as $post) { ?>
				<div class="row unitRow">
					<div class="col-md-12 celBlock">
						<a href="<?=get_the_permalink($post->ID)?>"><?=$post->post_title?></a>
					</div>
				</div>
			<? } ?>
		</div>

		<div class="col-md-6 col-lg-3 statusRow statCol-2">
			<div class="blockHeader">Latest forum topics</div>
			<? foreach ($topics as $topic) { ?>
				<div class="row unitRow">
					<div class="col-md-12 celBlock">
						<a href="/forum/topic/<?=$topic->id?>"><?=$topic->name?></a>
					</div>
				</div>
			<? } ?>
		</div>

		<div class="col-md-6 col-lg-3 statusRow statCol-1">
			<div class="blockHeader">Recent conversations</div>
			<? foreach ($user->getMessages() as $msg) { ?>
				<div class="row unitRow">
					<div class="col-md-12 celBlock">
						<a href="<?=$msg['link']?>/#lastrow"><?=$msg['title']?></a>
					</div>
				</div>
				<?php
			} ?>
		</div>

	</div>
 </div>
