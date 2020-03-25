<?php

class shailan_SubpagesWidget extends WP_Widget {
    /** constructor */
		public function __construct(){

			$widget_ops = array(
				'classname' => 'shailan-subpages-widget',
				'description' => __( 'Subpages list', 'subpages-extended' )
			);

			parent::__construct(
				'shailan-subpages-widget',
				__('Subpages Extended', 'subpages-extended'),
				$widget_ops
			);

			$this->alt_option_name = 'widget_shailan_subpages';
			$this->help_page = 'http://metinsaylan.com/docs/subpages-extended-help/';

			if ( is_active_widget(false, false, $this->id_base) )
				add_action( 'wp_head', array(&$this, 'styles') );

			$this->sort_options = array(
				'Post Title' => 'post_title',
				'Menu Order' => 'menu_order, post_title',
				'Date' => 'post_date',
				'Last Modified' => 'post_modified',
				'Page ID' => 'ID',
				'Page Author' => 'post_author',
				'Page Slug' => 'post_name'
			);

			$this->widget_defaults = array(
				'title' => '',
				'exclude' => '',
				'depth' => -1,
				'use_parent_title' => false,
				'exceptme' => false,
				'childof' => '',
				'sort_by' => 'menu_order, post_title',
				/* 'use_menu_labels' => false, */
				'link_on_title' => false,
				'rel' => ''
			);
		}

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
			global $post;
	    
			if ( is_null( $post ) ) {
				return;
			}

			extract( $args );
			$widget_options = wp_parse_args( $instance, $this->widget_defaults );
			extract( $widget_options, EXTR_SKIP );

			$use_parent_title = (bool) $use_parent_title;
			/* $use_menu_labels = (bool) $use_menu_labels; */
			$link_on_title = (bool) $link_on_title;

			// echo "<pre>".print_r($instance, true)."</pre>";

			$is_visible = false;

			if( '-1' == $childof ) {
				$childof = $post->ID;
			} elseif( '*parent*' == $childof ) {
				$childof = $post->post_parent;
				if($childof == 0){ $childof = $post->ID; } /* Top pages display sub pages only */
			} elseif( '*full-branch*' == $childof )	{
				if(!$post->post_parent){
					$childof = $post->ID;
				} else {
					$parent = $post->post_parent;
					$p = get_post($parent);
					while($p->post_parent) {
						$p = get_post($p->post_parent);
					}

					$childof = $p->ID;
				}
			} else {
				$is_visible = true;
			}

			if( is_page() || $is_visible ){

				$parent = $childof;

				// Setup page walker
				$walker = new Shailan_Walker_Page;
				$title_filter = 'walker_page_title';
				$walker->set_rel($rel);

				// Use parent title
				if( $use_parent_title ){ $title = get_the_title($parent); }

				// Link parent title
				if( $use_parent_title && $link_on_title ){
					$title = '<a href="' . get_permalink($parent) . '" title="' . esc_attr( wp_strip_all_tags( apply_filters( 'the_title', $title, $parent) ) ) . '">' . apply_filters( $title_filter, $title, $parent ) . '</a>';
				} else {
					$title = apply_filters( $title_filter, $title, $parent );
				}

				if( !$use_parent_title ){ $title = apply_filters('widget_title', $title); }

				$children=wp_list_pages( 'echo=0&child_of=' . $parent . '&title_li=' );

				$subpage_args = array(
					'depth'        => $depth,
					'show_date'    => 0,
					'date_format'  => get_option('date_format'),
					'child_of'     => $parent,
					'exclude'      => $exclude,
					'include'      => '',
					'title_li'     => '',
					'echo'         => 1,
					'authors'      => '',
					'sort_column'  => $sort_by,
					'link_before'  => '',
					'link_after'   => '',
					'walker' => $walker );

				if ($children) {
				?>
					  <?php echo $before_widget; ?>
						<?php if ( $title )
								echo $before_title . $title . $after_title;
						?>

					<div id="shailan-subpages-<?php echo $this->number; ?>">
						<ul class="subpages">
							<?php wp_list_pages($subpage_args); ?>
						</ul>
					</div>

					  <?php echo $after_widget; ?>
				<?php
				} else {
					echo "\n\t<!-- SUBPAGES : This page doesn't have any subpages. -->";
				};
			}
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) {

		$widget_options = wp_parse_args( $instance, $this->widget_defaults );
		extract( $widget_options, EXTR_SKIP );

    $title = esc_attr($title);
		$use_parent_title = (bool) $use_parent_title;
		$link_on_title = (bool) $link_on_title;

        ?>

<style>a.help-link {
    background: #eee;
    border-radius: 50%;
    display: inline-block;
    height: 16px;
    width: 16px;
    padding: 0;
    color: #0085ba;
    text-align: center;
    line-height: 15px;
    text-decoration: none;
    border: 1px solid;
}</style>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('use_parent_title'); ?>" name="<?php echo $this->get_field_name('use_parent_title'); ?>"<?php checked( $use_parent_title ); ?> /> <label for="<?php echo $this->get_field_id('use_parent_title'); ?>"><?php _e( 'Use page title as widget title' , 'subpages-extended' ); ?></label>
		<a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#title">?</a>
		</p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('link_on_title'); ?>" name="<?php echo $this->get_field_name('link_on_title'); ?>"<?php checked( $link_on_title ); ?> /> <label for="<?php echo $this->get_field_id('link_on_title'); ?>"><?php _e( 'Use link on widget title' , 'subpages-extended' ); ?></label>
		<a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#title-link">?</a>
		</p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title :'); ?> <a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#title">?</a> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('childof'); ?>"><?php _e('Parent (Subpages of):'); ?> <a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#parent">?</a> <?php
			$args = array(
				'selected' => $childof,
				'show_option_no_change' => '*Current page*',
				'show_option_none' => '*Parent of current page*',
				'option_none_value' => '*parent*',
				'name' => $this->get_field_name('childof'),
				'id' => $this->get_field_id('childof')
			); shailan_subpages_dropdown_pages($args); ?></label></p>

		<p><label for="<?php echo $this->get_field_id('rel'); ?>"><?php _e('Rel :'); ?> <a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#rel">?</a> <input class="widefat" id="<?php echo $this->get_field_id('rel'); ?>" name="<?php echo $this->get_field_name('rel'); ?>" type="text" value="<?php echo $rel; ?>" /></label></p>


		<p><label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude:'); ?> <a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#exclude">?</a> <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" /></label><br />
		<small>Page IDs, separated by commas.</small></p>

		<p><label for="<?php echo $this->get_field_id('sort_column'); ?>"><?php _e('Sort by :'); ?> <a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#sort-by">?</a> <select name="<?php echo $this->get_field_name('sort_column'); ?>" id="<?php echo $this->get_field_id('sort_column'); ?>" ><?php
  foreach ($this->sort_options as $value=>$key) {
  	$option = '<option value="'. $key .'" '. ( $key == $sort_column ? ' selected="selected"' : '' ) .'>';
	$option .= $value;
	$option .= '</option>\n';
	echo $option;
  }
 ?>
</select></label></p>

		<p><label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e('Depth:'); ?> <a class="help-link" target="_blank" rel="noopener" href="<?php echo $this->help_page; ?>#depth">?</a> <input class="widefat" id="<?php echo $this->get_field_id('depth'); ?>" name="<?php echo $this->get_field_name('depth'); ?>" type="text" value="<?php echo $depth; ?>" /></label><br />
		<small>Depth of menu.</small></p>

        <?php
	}

	function styles($instance){
		// additional styles will be printed here.
	}

} // class shailan_SubpagesWidget

// register widget
add_action('widgets_init', function(){
	register_widget("shailan_SubpagesWidget");
});
