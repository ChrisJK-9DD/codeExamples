<?php
/**
 * Template Name: People
 */

get_header(); 

	$heading = get_field("heading");
	$geolocationFilters = array();
	$geolocationFiltersTemp = array();
	$departmentFilters = array();
	$departmentFiltersTemp = array();

	$geolocations = get_terms( array(
		'taxonomy' => 'geographies',
		'hide_empty' => false,
	) );

	$departments  = get_terms( array(
		'taxonomy' => 'department',
		'hide_empty' => false,
	) );

	foreach ($geolocations as $geolocation) {
		array_push($geolocationFiltersTemp, $geolocation->slug);
	}
	foreach ($departments as $department) {
		array_push($departmentFiltersTemp, $department->slug);
	}

	$geolocationFilters = array_unique($geolocationFiltersTemp);
	$departmentFilters = array_unique($departmentFiltersTemp);

	if(get_field('hero_image')){ ?>
	<section class="fullWidth hero" style="background: linear-gradient(0deg, rgba(83,87,88,1), transparent), url('<?php echo get_field('hero_image');?>'); background-blend-mode: multiply; background-size: cover;">
	<?php } else {?>
		<section class="fullWidth hero">
		<?php } ?>
		<div class="heroWrapper">
			<div class="bonhillB">
				<img src="/wp-content/uploads/2021/08/brandmark-outlined-rev.svg">
			</div>
			<div class="heroHeading">
				<h2><?php echo $heading;?></h2>
			</div>
		</div>
	</section>
	<section class="brandIcons">
		<div class="filterButtons">
			<div id="category" class="buttonGroup" data-filter-group="category">
				<select>
					<option data-filter="executive-committee">Executive committee</option>
					<?php 
						foreach ($departmentFilters as $dep) {
							if($dep == 'executive-committee' || $dep == 'board-of-directors'){} else {
								echo '<option data-filter="' . $dep . '">' . ucfirst(str_replace("-", " ", $dep)) . '</option>';
							}
						}
					?>
				</select>
			</div>
			<div id="region" class="buttonGroup" data-filter-group="region">
				<select>
					<option class="first" data-filter="">Region</option>
					<?php 
						foreach ($geolocationFilters as $region) {
							if($region == "uk" || $region == "us"){ 
								echo '<option data-filter="' . $region . '">' . strtoupper(str_replace("-", " ", $region)) . '</option>';
							} else {
								echo '<option data-filter="' . $region . '">' . ucfirst(str_replace("-", " ", $region))  . '</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="department" id="executive-committee">
			<h3 class="underline">Executive committee</h3>
			<div class="brandGrid">
			<?php
				$row = 0;
				$executiveArgs = array(
					'post_type' => 'people',
					'tax_query' => array(
						array (
							'taxonomy' => 'levels',
							'field' => 'slug',
							'terms' => 'executive-committee',
						)
					),
					'orderby' => 'date',
					'order'   => 'DESC',
				);
				$executiveSel = new WP_Query($executiveArgs);
				if ($executiveSel->have_posts()) {
					while ( $executiveSel->have_posts() ) : $executiveSel->the_post();
						$classes = "";
						$geolocations = get_the_terms( $post, 'geographies' );
						foreach ($geolocations as $geolocation) {
							$classes .= $geolocation->slug . " ";
						} ?>
						<div class="brand person <?php echo $classes; ?>">
							<div class="personPicture">
								<img src="<?php echo get_field('picture');?>">
							</div>
							<div class="personInfo">
								<div class="personName"><?php echo get_the_title();?></div>
								<div class="personJob"><?php echo get_field('job_title', $post->ID);?></div>
								<div class="personEmail">E: <a href="mailto:<?php echo get_field('email');?>"><?php echo get_field('email');?></a></div>
							</div>
						</div>
					<?php endwhile;
				}
			?>
			</div>
		</div>

		<?php foreach ($departmentFilters as $department) {
			if($department == 'executive-committee' || $department == 'board-of-directors'){
			} else {
			echo '<div class="department" id="' . $department . '">';
			$title = ucfirst(str_replace("-", " ", $department));
			echo '<h3 class="underline">' . $title . '</h3>';
			$departmentArgs = array(
				'post_type' => 'people',
				'posts_per_page' => -1,
				'tax_query' => array(
					array (
						'taxonomy' => 'department',
						'field' => 'slug',
						'terms' => $department,
					)
				),
				'orderby' => 'title',
				'order'   => 'DESC',
			);
			$departmentSel = new WP_Query($departmentArgs);
			if ($departmentSel->have_posts()) {
				$num = $departmentSel->post_count; 
				while ( $departmentSel->have_posts() ) : $departmentSel->the_post();
					$levels = get_the_terms( $post, 'levels' );
					$display = true;
					foreach ($levels as $level) {
						if($level->slug == "board-of-directors"){
							$display = false;
						}
					}
					if($display){
						$classes = "";
						$geolocations = get_the_terms( $post, 'geographies' );
						foreach ($geolocations as $geolocation) {
							$classes .= $geolocation->slug . " ";
						}
						?>
						<div class="brand person <?php echo $classes; ?>">
            				<div class="personPicture">
            					<img src="<?php echo get_field('picture');?>">
            				</div>
            				<div class="personInfo">
            					<div class="personName"><?php echo get_the_title();?></div>
            					<div class="personJob"><?php echo get_field('job_title', $post->ID);?></div>
            					<div class="personEmail">E: <a target="_blank" href="mailto:<?php echo get_field('email');?>"><?php echo get_field('email');?></a></div>
            				</div>
            			</div>
				<?php }
				endwhile;
			}
			echo '</div>';
			}
		?>
		<?php } ?>
	</section>
<script>
	var personWidth = $(".brand.person").width();
	var regionToShow = "";
		$("#region select").on("change",function (){
			var $this = $("#region option:selected");
			$(".brand").show();
			regionToShow = $this.attr('data-filter');
			$(".brand:not(."+regionToShow+")").hide();
		});

		$("#category select").on("change",function (){
			var $this = $("#category option:selected");
			var departmentToShow = $this.attr('data-filter');
			$(".department").fadeOut("slow", function(){
				setTimeout(function(){
					$(".brand").show();
					if(regionToShow == ""){
						$(".department#"+departmentToShow).fadeIn("slow");
					} else {
						$(".department#"+departmentToShow).fadeIn("slow");
						$(".brand:not(."+regionToShow+")").hide();
					}
				},500);
			});
		});
</script>

<?php get_footer(); ?>