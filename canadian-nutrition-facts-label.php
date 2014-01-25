<?php
/*
Plugin Name:	Canadian Nutrition Facts Label
Plugin URI: 	http://dandelionwebdesign.com/downloads/canadian-nutrition-facts-label-plugin-wordpress/
Description:	Adds a custom post type "Labels" to generate a CANADIAN BILINGUAL Nutrition Facts Label to pages or posts with a shortcode. Use a shortcode [nutrition-label id=XXX] to add the label to any page or post.
Version: 1.0.0
Author: 		Dandelion Web Design Inc.
Author URI:		http://dandelionwebdesign.com/

Forked from: Easy Nutrition Label Plugin found at http://wordpress.org/plugins/easy-nutrition-facts-label

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
( at your option ) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/* ADDS */
add_shortcode( 'nutrition-label', 'nutr_label_shortcode');
add_action( 'wp_head', 'nutr_style');
add_action( 'init', 'nutr_init');
add_filter( 'manage_edit-nutrition-label_columns', 'nutr_modify_nutritional_label_table' );
add_filter( 'manage_posts_custom_column', 'nutr_modify_nutritional_label_table_row', 10, 2 );

add_action( 'add_meta_boxes', 'nutr_create_metaboxes' );
add_action( 'save_post', 'nutr_save_meta', 1, 2 );


/* RDA SETTINGS */
$rda = array(
		'totalfat' 			=> 65,
		'satfat' 			=> 20,
		'cholesterol' 		=> 300,
		'sodium' 			=> 2400,
		'carbohydrates' 	=> 300,
		'fiber' 			=> 25,
		'protein' 			=> 50,
		'vitamin_a' 		=> 5000,
		'vitamin_c' 		=> 60,
		'calcium' 			=> 1000,
		'iron' 				=> 18
		);


/* BASE NUTRIIONAL FIELDS */
$nutrional_fields = array(
					'servingsize' 	=> __('Serving Size'),
					'calories' 		=> __('Calories'),
					'totalfat' 		=> __('Total Fat'),
					'satfat' 		=> __('Saturated Fat'),
					'transfat' 		=> __('Trans. Fat'),
					'cholesterol' 	=> __('Cholesterol'),
					'sodium' 		=> __('Sodium'),
					'carbohydrates' => __('Carbohydrates'),
					'fiber' 		=> __('Fiber'),
					'sugars' 		=> __('Sugars'),
					'protein' 		=> __('Protein'),
					'vitamin_a'		=> __('Vit A'),
					'vitamin_c'		=> __('Vit C'),
					'calcium'		=> __('Calcium'),
					'iron'		    => __('Iron')
);



/*
 * Init
 */
function nutr_init()
{
	load_plugin_textdomain('wp-nutrition-label', false, 'wp-nutrition-label/languages/');

	$labels = array(
		'name' => __('Nutritional Labels'),
		'singular_name' => __('Label'),
		'add_new' => __('Add New'),
		'add_new_item' => __('Add New Label'),
		'edit_item' => __('Edit Label'),
		'new_item' => __('New Label'),
		'all_items' => __('All Labels'),
		'view_item' => __('View Label'),
		'search_items' => __('Search Labels'),
		'not_found' =>  __('No labels found'),
		'not_found_in_trash' => __('No labels found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => __('Labels')
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => false,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => false,
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => plugins_url('facts-menu-icon.png', __FILE__),
		'supports' => array( 'title' )
	); 
	register_post_type('nutrition-label', $args);
}


/*
 * Meta Box with Data
 */
function nutr_create_metaboxes()
{
	add_meta_box( 'nutr_create_metabox_1', 'Nutritional Label Options', 'nutr_create_metabox_1', 'nutrition-label', 'normal', 'default' );
}

function nutr_create_metabox_1()
{
	global $post, $nutrional_fields;	
	$meta_values = get_post_meta( $post->ID );
	
	$pages = get_posts( array( 'post_type' => 'page', 'numberposts' => -1 ) );
	$posts = get_posts( array( 'numberposts' => -1 ) );
	
	$selected_page_id = isset($meta_values['_pageid']) ? $meta_values['_pageid'][0] : 0;
	?>
	
	<div style="border-bottom: solid 1px #ccc; padding-bottom: 10px; padding-top: 10px;">
		<div style="width: 75px; margin-right: 10px; float: left; text-align: right; padding-top: 3px;">
			<?php _e('Page'); ?>
		</div>
		<select name="pageid" style="float: left;">
			<option value=""><?php _e('Select a Page...'); ?></option>
			<optgroup label="<?php _e('Pages'); ?>">
				<?php foreach($pages as $page) { ?>
				<option value="<?php echo $page->ID ?>"<?php if($selected_page_id == $page->ID) echo " SELECTED"; ?>><?php echo $page->post_title ?></option>
				<?php } ?>
			</optgroup>
			<optgroup label="<?php _e('Posts'); ?>">
				<?php foreach($posts as $post) { ?>
				<option value="<?php echo $post->ID ?>"<?php if($selected_page_id == $post->ID) echo " SELECTED"; ?>><?php echo $post->post_title ?></option>
				<?php } ?>
			</optgroup>
		</select>
		<div style="clear:both;"></div>
	</div>
	
	<?php
	foreach( $nutrional_fields as $name => $nutrional_field ) { ?>	
	<div style="padding: 3px 0;">
		<div style="width: 75px; margin-right: 10px; float: left; text-align: right; padding-top: 5px;">
			<?php echo $nutrional_field ?>
		</div>
		<input type="text" style=" float: left; width: 120px;" name="<?php echo $name ?>" value="<?php if(isset($meta_values['_' . $name])) { echo esc_attr( $meta_values['_' . $name][0] ); } ?>" />
	
		<div style="clear:both;"></div>
	</div>
<?php
	}
}

function nutr_save_meta( $post_id, $post ) 
{
	global $nutrional_fields;
	foreach( $nutrional_fields as $name => $nutrional_field ) 
	{
		if ( isset( $_POST[ $name ] ) ) { update_post_meta( $post_id, '_' . $name, strip_tags( $_POST[ $name ] ) ); }
	}
	
	if ( isset( $_POST[ 'pageid' ] ) ) { update_post_meta( $post_id, '_pageid', strip_tags( $_POST[ 'pageid' ] ) ); }
}


/*
 * Add Column to WordPress Admin 
 * Displays the shortcode needed to show label
 *
 * 2 Functions
 */
 
function nutr_modify_nutritional_label_table( $column ) 
{ 
	$columns = array(
		'cb'       			=> '<input type="checkbox" />',
		'title'    			=> 'Title',
		'nutr_shortcode'    => 'Shortcode',
		'nutr_page'    		=> 'Page',
		'date'     			=> 'Date'
	);

	return $columns;
}
function nutr_modify_nutritional_label_table_row( $column_name, $post_id ) 
{
 	if($column_name == "nutr_shortcode")
 	{
 		echo "[nutrition-label id={$post_id}]";
 	}
 	
 	if($column_name == "nutr_page")
 	{
 		echo get_the_title( get_post_meta( $post_id, "_pageid", true ) );
 	}
 	
}


/*
 * output our style sheet at the head of the file
 * because it's brief, we just embed it rather than force an extra http fetch
 *
 * @return void
 */
function nutr_style() 
{
?>
<style type='text/css'>
	.wp-nutrition-label { border: 1px solid #ccc; font-family: helvetica, arial, sans-serif; font-size: .9em; width: 22em; padding: 1em 1.25em 1em 1.25em; line-height: 1.4em; margin: 1em; }
	.wp-nutrition-label hr { border:none; border-bottom: solid 8px #666; margin: 3px 0px; }
	.wp-nutrition-label .heading { font-size: 2.6em; font-weight: 900; margin: 0; line-height: 1em; }
	.wp-nutrition-label .indent { margin-left: 1em; }
	.wp-nutrition-label .small { font-size: .8em; line-height: 1.2em; }
	.wp-nutrition-label .item_row { border-top: solid 1px #ccc; padding: 3px 0; }
	.wp-nutrition-label .amount-per { padding: 0 0 8px 0; }
	.wp-nutrition-label .daily-value { padding: 0 0 4px 0; font-weight: bold; text-align: right; border-top: solid 4px #666; }
	.wp-nutrition-label .f-left { float: left; }
	.wp-nutrition-label .f-right { float: right; }
	.wp-nutrition-label .noborder { border: none; }
	.wp-nutrition-label .amount { font-weight: 700; padding: 0; line-height: 1em; }
	
	.cf:before,.cf:after { content: " "; display: table;}
	.cf:after { clear: both; }
	.cf { *zoom: 1; }  
</style>
<?php
}


/*
 *
 * @param array $atts
 * @return string
 */
function nutr_label_shortcode($atts) 
{
	$id = (int) isset($atts['id']) ? $atts['id'] : false;
	$width = (int) isset($atts['width']) ? $atts['width'] : 22;	
	
	if($id) { return nutr_label_generate($id, $width); }
	{
		global $post;
	
		$label = get_posts( array( 'post_type' => 'nutrition-label', 'meta_key' => '_pageid', 'meta_value' => $post->ID ));
		
		if($label)
		{
			$label = reset($label);
			return nutr_label_generate( $label->ID, $width );
		}
	}
}


/*
 * @param integer $contains
 * @param integer $reference
 * @return integer
 */
function nutr_percentage($contains, $reference) 
{
	return round( $contains / $reference * 100 );
}


/*
 * @param array $args
 * @return string
 */
function nutr_label_generate( $id, $width = 22 ) 
{
	global $rda, $nutrional_fields;
	
	$label = get_post_meta( $id );
	
	if(!$label) { return false; }
	
	// GET VARIABLES
	foreach( $nutrional_fields as $name => $nutrional_field )
	{
		$$name = $label['_' . $name][0];	
	}

	// BUILD CALORIES IF WE DONT HAVE ANY
	if($calories == 0) 
	{
		$calories = ( ( $protein + $carbohydrates ) * 4 ) + ($totalfat * 9);
	}
		
	// WIDTH THE LABEL
	$style = '';
	if($width != 22) 
	{
		$style = " style='width: " . $width . "em; font-size: " . ( ( $width / 22 ) * .75 ) . "em;'";
	}
	
	$rtn = "";
	$rtn .= "<div class='wp-nutrition-label' id='wp-nutrition-label-$id' " . ($style ? $style : "") . ">\n";
	
	$rtn .= "	<div class='heading'>".__("Nutrition Facts")."</div>\n";
	$rtn .= "	<div class='heading'>".__("Valeur nutritive")."</div>\n";
	
	$rtn .= "	<div>" . __("Per") . " " . $servingsize . "</div>\n";
	$rtn .= "	<div>" . __("pour") . " " . $servingsize . "</div>\n";	
	$rtn .= "	<hr />\n";
	$rtn .= "	<div class='item_row cf noborder'>\n";
	$rtn .= "		<span class='f-left amount'>" . ("Amount") ."</span>\n";
	$rtn .= "		<span class='f-right amount'>% " . __("Daily Value") . "</span>\n";
	$rtn .= "	</div>\n";
	$rtn .= "	<div class='item_row cf noborder'>\n";
	$rtn .= "		<span class='f-left amount'>" . ("Teneur") ." </span>\n";
	$rtn .= "		<span class='f-right amount'>% " . ("valeur quotidienne") . "</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='item_row cf'>\n";
	$rtn .= "		<span class='f-left'>" . __("Calories / Calories") . " " . $calories . "</span>\n";
	$rtn .= "	</div>\n";
	
	
	$rtn .= "	<div class='item_row cf'>\n";
	$rtn .= "		<span class='f-left'><strong>" . __("Fat / Lipides") . "</strong> " . $totalfat . "g</span>\n";
	$rtn .= "		<span class='f-right'>" . nutr_percentage($totalfat, $rda['totalfat']) . "%</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='indent item_row cf'>\n";
	$rtn .= "		<span class='f-left'>" . __("Saturated / saturés") . " " . $satfat . "g" . "</span>\n";
	$rtn .= "		<span class='f-right'>" . nutr_percentage($satfat, $rda['satfat']) . "%</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='indent noborder cf'>\n";
	$rtn .= "		<span>" . __("+ Trans / trans") . " " . $transfat . "g</span>";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='item_row cf'>\n";
	$rtn .= "		<span class='f-left'><strong>" . __("Cholesterol / Cholestérol") . "</strong> " . $cholesterol . "mg</span>\n";
	$rtn .= "		<span class='f-right'>" . nutr_percentage($cholesterol, $rda['cholesterol']) . "%</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='item_row cf'>\n";
	$rtn .= "		<span class='f-left'><strong>" . __("Sodium / Sodium")."</strong> " . $sodium . "mg</span>\n";
	$rtn .= "		<span class='f-right'>" . nutr_percentage($sodium, $rda['sodium']) . "%</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='item_row cf'>\n";
	$rtn .= "		<span class='f-left'><strong>" . __("Carbohydrate / Glucides") . "</strong> " . $carbohydrates . "g</span>\n";
	$rtn .= "		<span class='f-right'>" . nutr_percentage($carbohydrates, $rda['carbohydrates']) . "%</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='indent item_row cf'>\n";
	$rtn .= "		<span class='f-left'>" . __("Fiber / Fibres")." ".$fiber . "g</span>\n";
	$rtn .= "		<span class='f-right'>" . nutr_percentage($fiber, $rda['fiber']) . "%</span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='indent item_row cf'>\n";
	$rtn .= "		<span>".__("Sugars / Sucres")." ".$sugars."g</span>";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<div class='item_row cf'>\n";
	$rtn .= "		<span class='f-left'><strong>".__("Protein / Protéines")."</strong> ".$protein."g</span>\n";
	$rtn .= "		<span class='f-right'></span>\n";
	$rtn .= "	</div>\n";
	
	$rtn .= "	<hr />\n";
	
    $rtn .= "	<div class='item_row noborder cf'>\n";
    $rtn .= "		<span class='f-left'>Vitamin A / Vitamine A</span>\n";
    $rtn .= "		<span class='f-right'>" . $vitamin_a.  "%</span>\n";
	$rtn .= "	</div>\n";

	$rtn .= "	<div class='item_row cf'>\n";
    $rtn .= "		<span class='f-left'>Vitamin C / Vitamine C</span>\n";
    $rtn .= "		<span class='f-right'>" . $vitamin_c.  "%</span>\n";
	$rtn .= "	</div>\n";
	
   $rtn .= "	<div class='item_row cf'>\n";
   $rtn .= "		<span class='f-left'>Calcium / Calcium </span>\n";
   $rtn .= "		<span class='f-right'>" .$calcium.  "%</span>\n";
   $rtn .= "	</div>\n";

   $rtn .= "	<div class='item_row cf'>\n";
   $rtn .= "		<span class='f-left'>Iron / Fer </span>\n";
   $rtn .= "		<span class='f-right'>" . $iron .  "%</span>\n";
   $rtn .= "	</div>\n";	
	
	
  
 
  
	$rtn .= "</div> <!-- /wp-nutrition-label -->\n\n";
	return $rtn;  
}

?>