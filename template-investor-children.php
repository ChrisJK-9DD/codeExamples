<?php
/**
 * Template Name: Page Builder
 */

get_header();

	global $post;
	// var_dump($post->post_parent);
	// var_dump($post->ID);

	$heading = get_the_title();
	if(get_field('subTitle')){
		$subHeading = get_field('subTitle');
	} elseif($post->ID == 1350){
		$subHeading = "Board of directors";
	}
	

?>
	<?php if($post->post_parent == 1980 || $post->post_parent == 1981 || $post->post_parent == 2320|| $post->ID == 1372 || $post->ID == 1350){?>
		<?php if(get_field('hero_image')){ ?>
		<section class="fullWidth hero" style="background-image: linear-gradient(0deg, rgba(83,87,88,1), transparent), url('<?php echo get_field('hero_image');?>'); background-blend-mode: multiply; background-size: cover;">
		<?php } else {?>
			<section class="fullWidth hero">
			<?php } ?>
			<div class="heroWrapper">
				<div class="bonhillB">
					<img src="/wp-content/uploads/2021/08/brandmark-outlined-rev.svg">
				</div>
				<div class="heroHeading">
					<h2><?php echo $subHeading;?></h2>
				</div>
			</div>
		</section>
	<?php } else {
		if(get_field('hero_intro_text')) { ?>
		<section class="fullWidth heroMini intro_text">
		<?php } else { ?>
		<section class="fullWidth heroMini">
		<?php } ?>
			<div class="heroWrapper">
				<div class="heroHeading">
					<h2><?php echo $heading;?></h2>
					<?php if(get_field('hero_intro_text')) {?>
						<p><?php echo get_field('hero_intro_text');?></p>
					<?php } ?>
					<?php if(get_field('subTitle')){ ?> 
						<h6><?php echo $subHeading;?></h6>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php } ?>
	<section class="content careers">
		<div class="full">
			<?php 
				if(get_field('iframe_url')){?>
					<iframe id="iframe" src="<?php echo get_field('iframe_url');?>"></iframe>
				<?php } else {
					require('page-builder.php');
					$blockNumber = 1;
					if($data){
						foreach ($data as $block) {
							$type = $block->block_type;
							if($type == "large_heading"){
								require('templates/text-only-news.php');
							} elseif($type == "text_only"){
								require('templates/text-only.php');
							} elseif($type == "contacts"){
								// var_dump($block);
								require('templates/contacts.php');
							} elseif($type == "video") {
								if(is_page('1386')){
									require('templates/video-news.php');
								} else {
									require('templates/video.php');
								}
							} elseif($type == "brand_information"){
								require('templates/brand_information.php');
							} elseif($type == "cards") {
								require('templates/cards.php');
							} elseif($type == "board_of_directors") {
								require('templates/board_of_directors.php');
							} elseif($type == "table") {
								require('templates/table.php');
							} elseif($type == "downloads") {
								require('templates/downloads.php');
							} elseif($type == "reports_presentations") {
								require('templates/reports.php');
							} elseif($type == "news_rns_tabs") {
								require('templates/news_rns_tabs.php');
							} elseif($type == "history") {
								require('templates/history.php');
							} elseif($type == "columns") {
								require('templates/columns.php');
							} elseif($type == "brand_list") {
								require('templates/brand_list.php');
							}
						}
					}
				}
				if(is_page('2159')){
					require('templates/vacancies.php');
				}
			?>
		</div>
	</section>
<script>

</script>

<?php get_footer(); ?>