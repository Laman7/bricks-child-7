<?php 
/**
 * Register/enqueue custom scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
	// Enqueue syle.css then autocss.
	wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime( get_stylesheet_directory() . '/style.css' ) );
	wp_enqueue_style( 'bricks-child-auto', get_stylesheet_directory_uri() . '/auto-x.css', [], filemtime( get_stylesheet_directory() . '/auto-x.css' ) );

	// Disable jQuery if not using
	wp_enqueue_script( 'jquery' );
} );

/**
 * Register custom elements
 */
add_action( 'init', function() {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ( $element_files as $file ) {
    \Bricks\Elements::register_element( $file );
  }
}, 11 );


/**
 * Add text strings to builder
 */
add_filter( 'bricks/builder/i18n', function( $i18n ) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__( 'Custom', 'bricks' );

  return $i18n;
} );

/**
 * Custom save messages
 */
add_filter( 'bricks/builder/save_messages', function( $messages ) {
	// First option: Add individual save message
	$messages[] = 'Yasss';

	// Second option: Replace all save messages
	$messages = [
		'Done',
		'Cool',
		'High five!',
	];

  return $messages;
} );

/** 
 * Add custom map style
 */
// add_filter( 'bricks/builder/map_styles', function( $map_styles ) {
//   // Shades of grey (https://snazzymaps.com/style/38/shades-of-grey)
//   $map_styles['shadesOfGrey'] = [
//     'label' => esc_html__( 'Shades of grey', 'bricks' ),
//     'style' => '[ { "featureType": "all", "elementType": "labels.text.fill", "stylers": [ { "saturation": 36 }, { "color": "#000000" }, { "lightness": 40 } ] }, { "featureType": "all", "elementType": "labels.text.stroke", "stylers": [ { "visibility": "on" }, { "color": "#000000" }, { "lightness": 16 } ] }, { "featureType": "all", "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "featureType": "administrative", "elementType": "geometry.fill", "stylers": [ { "color": "#000000" }, { "lightness": 20 } ] }, { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [ { "color": "#000000" }, { "lightness": 17 }, { "weight": 1.2 } ] }, { "featureType": "landscape", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 20 } ] }, { "featureType": "poi", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 21 } ] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#000000" }, { "lightness": 17 } ] }, { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [ { "color": "#000000" }, { "lightness": 29 }, { "weight": 0.2 } ] }, { "featureType": "road.arterial", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 18 } ] }, { "featureType": "road.local", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 16 } ] }, { "featureType": "transit", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 19 } ] }, { "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 17 } ] } ]'
//   ];

//   return $map_styles;
// } );

if ( ! defined( 'BRICKS_MAX_REVISIONS_TO_KEEP' ) ) {
    define( 'BRICKS_MAX_REVISIONS_TO_KEEP', 10 );
}

// Awesome login
function laman7_login() {
    echo '<style type="text/css">
		#login {width: 25rem; padding: 0}
        #login h1{ display:none}
		body {background-color: #151515}
		.login form {background-color: #222; color: #eee; border-radius:.5rem; border-color: #333; padding: 2rem}
		.login label {font-size: 1rem}
		.wp-core-ui .button.button-large {border-radius:2rem; font-size:1rem; padding: 0.5rem 2rem}
		#login p {padding:1rem 0}
		#new-logo {margin:10vh auto 0; display:block; text-align:center}
    </style>
	<a href="https://laman7.com" id="new-logo"><img src="https://assetl7.s3.ap-southeast-1.amazonaws.com/laman7-logo-web.png"></a>
	';
}
add_action('login_head', 'laman7_login'); 

//Remove all except Medium size image
function sgr_filter_image_sizes( $sizes) {
	unset( $sizes['thumbnail']);
	/* unset( $sizes['medium']); */
	unset( $sizes['medium_large']);
	unset( $sizes['large']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'sgr_filter_image_sizes');

//WP Allow SVG
add_filter("upload_mimes", function ($mimes) {
    $mimes["svg"] = "image/svg+xml";
        return $mimes;
    });
    
add_filter("wp_check_filetype_and_ext", function ($result, $file, $filename, $mimes) {
    if (!$result["ext"] || !$result["type"]) {
        $filetype = wp_check_filetype($filename, $mimes);
        $ext = $filetype["ext"];
        $type = $filetype["type"];
        $allowed_types = [
            "svg" => [
                "image/svg+xml"
            ],
        ];
        if (isset($allowed_types[$ext]) && in_array($type, $allowed_types[$ext])) {
            $result = [
                "ext" => $ext,
                "type" => $type,
                "proper_filename" => $filename
            ];
        }
    }
    return $result;
}, 10, 4);

// Make uploads filename lower case - make it easier to link
add_filter( 'sanitize_file_name', 'mb_strtolower' );

// Limit Post Revision
//define( 'WP_POST_REVISIONS', 10 );

// Remove Author Name in Social Graph
add_filter( 'oembed_response_data', 'disable_embeds_filter_oembed_response_data_' );
function disable_embeds_filter_oembed_response_data_( $data ) {
    unset($data['author_url']);
    unset($data['author_name']);
    return $data;
}

// Remove WP version
function wpb_remove_version() {
return '';
}
add_filter('the_generator', 'wpb_remove_version');

// Read time, use in loop, {echo:read_time}
add_filter( 'bricks/code/echo_function_names', function() {
  return [
    'read_time',
	'get_post_type',
  ];
} );
 
function read_time() {
    // Strip HTML tags and extract words from the content
  	$post_content = get_the_content();
    $words = str_word_count(strip_tags($post_content));

    // Calculate estimated reading time (assuming 8 words per minute)
    $reading_time = ceil($words / 238);
    return $reading_time;
}

// Add default image setting to ACF image fields
add_action('acf/render_field_settings/type=image', 'add_default_value_to_image_field');
function add_default_value_to_image_field($field) {
	acf_render_field_setting( $field, array(
		'label'			=> 'Default Image',
		'instructions'		=> 'Appears when creating a new post',
		'type'			=> 'image',
		'name'			=> 'default_value',
	));
}
//Disable XMLRPC
add_filter('xmlrpc_enabled', '__return_false');

//Remove DuoTone WP
function remove_svg() {
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
	remove_action( 'in_admin_header', 'wp_global_styles_render_svg_filters' );
}
add_action('after_setup_theme', 'remove_svg', 10, 0);

// Add media size and variation Edwin, 8 Oct https://laman7.slab.com/posts/wp-media-size-show-dimension-and-size-2gej73hu
// Add a custom column for media sizes in the WordPress Media Library
function add_media_sizes_column( $columns ) {
    $columns['media_sizes'] = __( 'Image Sizes', 'textdomain' ); // Add new column for media sizes
    return $columns;
}
add_filter( 'manage_upload_columns', 'add_media_sizes_column' );

// Populate the custom column with the available image sizes and the original image
function show_media_sizes_column_content( $column_name, $post_id ) {
    if ( 'media_sizes' === $column_name ) {
        // Get the original file path
        $file_path = get_attached_file( $post_id );

        // Get original image size and dimensions
        $original_size = filesize( $file_path );
        $original_dimensions = getimagesize( $file_path ); // Get original dimensions (width, height)

        // Format original size to human-readable format (KB/MB)
        $original_size_kb = size_format( $original_size, 2 ); 

        // Display the original image dimensions and size
        $output = sprintf(
            'Original: %dx%d (%s)<br>',
            esc_html( $original_dimensions[0] ), // Width
            esc_html( $original_dimensions[1] ),  // Height
            esc_html( $original_size_kb )          // Size
        );

        // Get metadata and check if there are image sizes
        $metadata = wp_get_attachment_metadata( $post_id );
        
        if ( isset( $metadata['sizes'] ) ) {
            $sizes = $metadata['sizes'];
            
            // Loop through each image size and display its dimensions and file size
            foreach ( $sizes as $size => $info ) {
                $file_path = path_join( dirname( $file_path ), $info['file'] );
                $file_size = filesize( $file_path );
                $file_size_kb = size_format( $file_size, 2 ); // Convert to human-readable format (KB/MB)
                
                $output .= sprintf(
                    '%s: %dx%d (%s)<br>',
                    esc_html( $size ),
                    esc_html( $info['width'] ),
                    esc_html( $info['height'] ),
                    esc_html( $file_size_kb )
                );
            }
        }
        echo $output;
    }
}
add_action( 'manage_media_custom_column', 'show_media_sizes_column_content', 10, 2 );
//Block weird searches
add_action('init', function () {
    if (isset($_GET['s'])) {
        $query_string = $_GET['s'];

        // Block if the search query exceeds 20 characters
        if (strlen($query_string) > 20) {
            wp_die('Blocked: Search query too long.', 'Blocked', ['response' => 403]);
        }

        // Block if the search query contains special characters
        if (preg_match('/[\/\!\#\$\^\&\*\(\)\{\}\[\]\;\:\'\"\<\>\?~\`]/', $query_string)) {
            wp_die('Blocked: Search query contains invalid characters.', 'Blocked', ['response' => 403]);
        }
    }
});