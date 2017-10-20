<?php
/*
Plugin Name: Subpages Extended
Plugin URI: http://metinsaylan.com/projects/wordpress/subpages-extended/
Description: List sub pages of any page, using shortcode and widget. Visit <a href="options-general.php?page=subpages-extended">settings page</a> for auto-insert option.
Version: 1.5.1
Author: Metin Saylan
Author URI: http://metinsaylan.com
Text Domain: subpages-extended
*/

global $subpages_indexes;

// Utilities
include_once('class-shailan-walker-page.php');
include_once('subpages-extended-util-dropdown-pages.php');

// Widget & shortcode
include_once('subpages-extended.php'); // the widget
include_once('subpages-extended-shortcode.php'); // the shortcode

// Options & meta boxes
include_once('subpages-extended-admin.php');
include_once('subpages-menu-label-metabox.php');

// Filters
include_once('subpages-extended-filter-auto-insert.php');
include_once('subpages-extended-filter-page-title.php');
