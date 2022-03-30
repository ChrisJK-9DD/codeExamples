<?php
/**
 * Template Name: Brands
 */

get_header(); 

	// $brands = get_field('brands');
	$brandsHeading = get_field("brands_heading");
	$brandsIntro = get_field("brands_intro_text");
	$geolocationFilters = array();
	$geolocationFiltersTemp = array();
	$categoryFilters = array();
	$categoryFiltersTemp = array();

	$brandArgs = array(
		'post_type' => 'brands',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order'   => 'ASC',
	);

	$geolocations = get_terms( array(
    	'taxonomy' => 'geographies',
    	'hide_empty' => true,
	) );

	$categories  = get_terms( array(
    	'taxonomy' => 'topics',
    	'hide_empty' => true,
	) );

	foreach ($geolocations as $geolocation) {
		array_push($geolocationFiltersTemp, $geolocation->slug);
	}
	foreach ($categories as $category) {
		array_push($categoryFiltersTemp, $category->slug);
	}

	$geolocationFilters = array_unique($geolocationFiltersTemp);
	$categoryFilters = array_unique($categoryFiltersTemp);

	if(get_field('hero_image')){ ?>
	<section class="fullWidth hero" style="background-image: linear-gradient(0deg, rgba(83,87,88,1), transparent), url('<?php echo get_field('hero_image');?>'); background-blend-mode: multiply; background-size: cover;">
	<?php } else {?>
		<section class="fullWidth hero">
		<?php } ?>
		<div class="heroWrapper">
			<div class="bonhillB">
				<img src="/wp-content/uploads/2021/08/brandmark-outlined-rev.svg">
			</div>
			<div class="heroHeading">
				<h2><?php echo $brandsHeading;?></h2>
			</div>
		</div>
	</section>
	<section class="brandIcons">
		<div class="filterButtons">
			<div id="viewAll" class="buttonGroup" data-filter-group="all">
				<span data-filter="*">View all</span>
			</div>
			<div id="category" class="buttonGroup" data-filter-group="category">
				<select>
					<option data-filter="">Category</option>
					<?php 
						foreach ($categoryFilters as $cat) {
							echo '<option data-filter="' . $cat . '">' . ucfirst(str_replace("-", " ", $cat)) . '</option>';
						}
					?>
				</select>
			</div>
			<div id="region" class="buttonGroup" data-filter-group="region">
				<select>
					<option data-filter="">Region</option>
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
		<div class="brandGrid">
		<?php
			$brandSel = new WP_Query($brandArgs);
			if ($brandSel->have_posts()) {
				while ( $brandSel->have_posts() ) : $brandSel->the_post();
					$classes = "";
					$geolocations = get_the_terms( $post, 'geographies' );
					foreach ($geolocations as $geolocation) {
						$classes .= $geolocation->slug . " ";
					}
					$categories = get_the_terms( $post, 'topics' );
					foreach ($categories as $category) {
						$classes .= $category->slug . " ";
					}
					echo '<div class="brand ' . $classes . '">';
            		echo '<a href="' . get_the_permalink() . '"><img src="' . get_field('logo') . '"></a>';
            		echo '</div>';
				endwhile;
			}
		?>
		</div>
	</section>
<script>
		var regionToShow = "";
		$("#region select").on("change",function (){
			var $this = $("#region option:selected");
			regionToShow = $this.attr('data-filter');
			if(regionToShow == ""){
				if(categoryToShow != "") {
					console.log(categoryToShow+","+regionToShow);
					$(".brand:not(."+categoryToShow+")").hide();
					$(".brand."+categoryToShow).show();
				} else {
					console.log(categoryToShow+","+regionToShow);
					$(".brand").show();
				}
			} else {
				if(categoryToShow != "") {
					console.log(categoryToShow+","+regionToShow);
					$(".brand:not(."+regionToShow+")").hide();
					$(".brand:not("+categoryToShow+")").hide();
					$(".brand."+regionToShow+"."+categoryToShow).show();
				} else {
					console.log(categoryToShow+","+regionToShow);
					$(".brand:not(."+regionToShow+")").hide();
					$(".brand."+regionToShow).show();
				}
			}
		});

		var categoryToShow = "";
		$("#category select").on("change",function (){
			var $this = $("#category option:selected");
			categoryToShow = $this.attr('data-filter');
			if(categoryToShow == ""){
				if(regionToShow != ""){
					console.log(categoryToShow+","+regionToShow);
					$(".brand:not(."+regionToShow+")").hide();
					$(".brand."+regionToShow).show();
				} else {
					console.log(categoryToShow+","+regionToShow);
					$(".brand").show();
				}
			} else {
				if(regionToShow != "") {
					console.log(categoryToShow+","+regionToShow);
					$(".brand:not(."+categoryToShow+")").hide();
					$(".brand:not(."+regionToShow+")").hide();
					$(".brand."+categoryToShow+"."+regionToShow).show();
				} else {
					console.log(categoryToShow+","+regionToShow);
					$(".brand:not(."+categoryToShow+")").hide();
					$(".brand."+categoryToShow).show();
				}
			}
		});

		$("#viewAll span").on("click", function(){
			$("#region select option:first-child").attr("selected","selected");
			$("#category select option:first-child").attr("selected","selected");
			$(".brand").show();
		})
	// });
	function concatValues( obj ) {
			var value = '';
			for ( var prop in obj ) {
				value += obj[ prop ];
			}
			return value;
		}
</script>

<?php get_footer(); ?>