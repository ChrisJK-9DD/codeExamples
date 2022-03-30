<?php

?>

<?php

	// Get all page builder fields
	$page_builder = get_field('page_builder');

	if (!empty($page_builder)) {

		// Set up array
		$data = [];

		// Loop through each block
		foreach ($page_builder as $block) {
			if ($block['acf_fc_layout'] == 'video') {

				// Get the full oembed iframe code
				$video_iframe = $block['video_url'];

				// Break down the iframe code
				preg_match('/src="(.+?)"/', $video_iframe, $video_iframe_matches);

				// Get just the video URL
				$video_url = $video_iframe_matches[1];

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'heading' => $block['heading'],
				    'video_text' => $block['video_text'],
				    'video_url' => $video_url,
				    'video_type' => $block['video_type'],
				    'brightcove_iframe' => $block['brightcove_iframe'],
				    'poster_image' => $block['poster_image']['sizes']['large'],
				];
				
				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'text_only') {
				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'content' => $block['content']
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'large_heading') {
				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'heading' => $block['heading'],
				    'content' => $block['content'],
				    'heading_image' => $block['heading_image'],
				    'more_link' => $block['more_link']
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'contacts') {
				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'contactList' => $block['contact_list']
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'brand_information') {
				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'formats' => $block['formats'],
				    'audience' => $block['audience'],
				    'reach' => $block['reach'],
				    'geography' => $block['geography']
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'tabs') {
				$tabs_data = $block['tabs'];
				$tabs = [];

				foreach ($tabs_data as $tab) {

				    // Check if a CTA should be added
				    $add_link = ($tab['add_cta_link_to_tab'] == true) ? true : false;

				    // If a CTA should be added, get link text and link
				    $cta_text = ($add_link == true ? $tab['cta_text'] : '');
				    $cta_link = ($add_link == true ? $tab['cta_link'] : '');

				    // Generate an anchor for this tab
				    $anchor_slug = slugify($tab['tab_title']);

				    $this_tab = (object) [
				        'title' => $tab['tab_title'],
				        'anchor' => $anchor_slug,
				        'content' => $tab['tab_content'],
				        'add_link' => $add_link,
				        'cta_text' => $cta_text,
				        'cta_link' => $cta_link
				    ];

				    array_push($tabs, $this_tab);
				}
				
				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'background_gradient' => $block['background_gradient'],
				    'heading' => $block['heading'],
				    'tabs' => $tabs
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'cards') {
				$card_data = $block['cards'];
				$cards = [];

				foreach ($card_data as $card) {

				    // Check if this card should have an icon
				    $add_icon = ($card['add_icon'] == true) ? true : false;

				    // If an icon should be added, get icon and background image
				    $icon = ($add_icon == true ? $card['icon']['sizes']['large'] : '');
				    $icon_background = ($add_icon == true ? $card['icon_background']['sizes']['large'] : '');

				    // Check if this card should have a link
				    $card_link = ($card['add_link'] == true) ? true : false;

				    // If a link should be added, get link and link text
				    $link = ($card_link == true ? $card['link'] : '');
				    $link_text = ($card_link == true ? $card['link_text'] : '');

				    $this_card = (object) [
				        'title' => $card['card_title'],
				        'content' => $card['card_main_content'],
				        'icon' => $icon,
				        'icon_background' => $icon_background,
				        'link' => $link,
				        'link_text' => $link_text
				    ];

				    array_push($cards, $this_card);
				}

				// Check if this card should have an CTA
				$add_cta = ($block['add_call_to_action'] == true) ? true : false;

				// If a CTA should be added, get link text and url
				$cta_text = ($add_cta == true ? $block['call_to_action_button_text'] : '');
				$cta_link = ($add_cta == true ? $block['call_to_action_button_link'] : '');
				
				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'number_of_cards' => $block['number_of_cards_per_row'],
				    'heading' => $block['cards_heading'],
				    'intro_text' => $block['intro_text'],
				    'cards' => $cards,
				    'cta_text' => $cta_text,
				    'cta_link' => $cta_link
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'board_of_directors') {
				global $post;

				$board = [];
		
				// set up our custom query for fetching all child pages of the current page
				$query = new WP_Query([
				    'post_type'             => 'board',
				    'posts_per_page'        => 99,
				    'orderby'               => 'menu_order',
				    'order'                 => 'ASC',
				]);
		
				// Loop through the results of our query (returned posts specifically)
				// and store in a new object, each pushed to our main $data object
				foreach ($query->posts as $p) {

				    // Get full bio
				    $content = get_field('bio', $p->ID);

				    // Strip HTML tags out of content
				    $stripped_content = strip_tags($content);

				    // Explode string to full stops
				    $content_sentence = explode('.', $stripped_content);

				    // Get the first sentence and re-add full stop to end
				    $excerpt_trimmed = $content_sentence[0] .= '.';
		
				    $this_board = (object) [
				        'name' => get_the_title($p->ID),
				        'permalink' => get_the_permalink($p->ID),
				        'photo' => get_field('photo', $p->ID)['sizes']['medium'],
				        'title' => get_field('title', $p->ID),
				        'bio' => $content,
				        'excerpt' => $excerpt_trimmed,
				    ];
		
				    array_push($board, $this_board);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'board' => $board
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'table') {
				$table_header_data = $block['table']['header'];
				$table_body = $block['table']['body'];

				$header_rows = [];
				$rows = [];

				foreach ($table_header_data as $row) {
				    $this_row = (object) [
				        'cell_data' => $row['c']
				    ];

				    array_push($header_rows, $this_row);
				}


				foreach ($table_body as $row) {
				    $cell_count = 0;
				    $this_row = [];

				    foreach ($row as $cell) {
				        $this_cell = (object) [
				            'header' => $table_header_data[$cell_count]['c'],
				            'cell_data' => $cell['c'],
				        ];

				        $cell_count++;
				        
				        array_push($this_row, $this_cell);
				    }

				    array_push($rows, $this_row);
				}

				// Check if the table should have a heading
				$add_heading = ($block['add_heading_to_table'] == true) ? true : false;

				// If the table should have a heading, return it
				$header = ($add_heading == true ? $block['table_heading'] : '');

				// Check if the table should have an intro
				$add_intro = ($block['add_intro_to_table'] == true) ? true : false;

				// If the table should have an intro, return it
				$intro = ($add_heading == true ? $block['table_intro'] : '');

				$this_block = (object) [
				    'block_type'  => $block['acf_fc_layout'],
				    'table_head'  => $header_rows,
				    'rows'        => $rows,
				    'header'      => $header,
				    'intro'       => $intro,
				    'right_align' => $block['right_align_columns'],
				    'background'  => $block['background_image']
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'downloads') {
				$files = [];

				foreach ($block['downloads'] as $file) {
				    $this_file = (object) [
				        'title' => $file['file_name'],
				        'url' => $file['file']
				    ];

				    array_push($files, $this_file);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'background' => $block['background_image'],
				    'files' => $files,
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'reports_presentations') {
				$files = [];

				foreach ($block['downloads'] as $file) {
				    $this_file = (object) [
				        'title' => $file['file_name'],
				        'type' => $file['annual_interim'],
				        'url' => $file['file']
				    ];

				    array_push($files, $this_file);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'annual_interim' => $block['annual_interim'],
				    'background' => $block['background_image'],
				    'files' => $files,
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'news_rns_tabs') {
				$rns = [];
				$news = [];

				// Ascends the current directory by six levels
				// This is the root web folder from Trellis (site)
				$rns_json_path = 'feed-data/rns.json';
				
				$rns_json = file_get_contents($rns_json_path);

				// Decode to PHP object
				$rns_data = json_decode($rns_json);

				foreach ($rns_data as $post) {

				    // Get the raw (string) date
				    $raw_date = $post->date;

				    // Convert date from string -> Unix timestamp
				    $date_timestamp = strtotime($raw_date);

				    // Convert from Unix timestamp preferred format
				    $date = date('d F Y', $date_timestamp);

				    // Get the ID of this RNS item
				    $rns_id = $post->id;

				    // Form the link to the RNS item, interpolated with the ID
				    $link = 'http://tools.euroland.com/tools/PressReleases/GetPressRelease/?ID=' . $rns_id . '&lang=en-GB&companycode=services';

				    $this_rns = (object) [
				        'title' => $post->title,
				        'date' => $date,
				        'permalink' => $link
				    ];

				    array_push($rns, $this_rns);
				}

				// set up our custom query for fetching 3 most recent news items
				$query = new WP_Query([
				    'post_type'             => 'news',
				    'posts_per_page'        => 3,
				]);
		
				// Loop through the results of our query (returned posts specifically)
				// and store in a new object, each pushed to our main $data object
				foreach ($query->posts as $p) {
				    $this_news = (object) [
				        'title' => get_the_title($p->ID),
				        'permalink' => get_the_permalink($p->ID),
				        'date' => get_the_time('d F Y', $p->ID),
				    ];
		
				    array_push($news, $this_news);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'rns' => $rns,
				    'news' => $news
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'history') {
				$history_data = $block['history'];
				$history = [];

				foreach ($history_data as $event) {
				    $this_event = (object) [
				        'year' => $event['year'],
				        'event' => $event['event'],
				    ];

				    array_push($history, $this_event);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'history' => $history,
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'columns') {
				$columns_data = $block['columns'];
				$columns = [];

				foreach ($columns_data as $column) {
				    $this_column = (object) [
				        'content' => $column['column_content']
				    ];

				    array_push($columns, $this_column);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'title' => $block['columns_title'],
				    'intro' => $block['columns_intro'],
				    'background_color' => $block['background_color'],
				    'column_number' => $block['number_of_columns'],
				    'columns' => $columns,
				];

				array_push($data, $this_block);
			} elseif ($block['acf_fc_layout'] == 'brand_list') {

				$brands = [];
		
				// set up our custom query for fetching all child pages of the current page
				$query = new WP_Query([
				    'post_type' => 'brands',
				    'posts_per_page' => 12,
				    'facetwp' => true,
				]);
		
				// Loop through the results of our query (returned posts specifically)
				// and store in a new object, each pushed to our main $data object
				foreach ($query->posts as $p) {

				    $this_brand = (object) [
				        'name' => get_the_title($p->ID),
				        'permalink' => get_the_permalink($p->ID),
				        'logo' => get_field('logo', $p->ID)['sizes']['medium'],
				        'logo_alt' => get_field('logo', $p->ID)['alt'] ?? '',
				    ];
		
				    array_push($brands, $this_brand);
				}

				$this_block = (object) [
				    'block_type' => $block['acf_fc_layout'],
				    'brands' => $brands,
				    'geographies' => facetwp_display('facet', 'geographies'),
				    'topics' => facetwp_display('facet', 'topics'),
				    'formats' => facetwp_display('facet', 'formats'),
				    'services' => facetwp_display('facet', 'services'),
				];

				array_push($data, $this_block);
			}
		}

		$data = (object) $data;
	}
?>