<?php
/**
 * Header Template.
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<?php wp_head(); ?>
	<script>
	const _dittoURI_ = "<?php echo get_template_directory_uri(); ?>",
			_dittoURL_ = "<?php echo get_site_url(); ?>";
	</script>
</head>

<body <?php body_class(); ?>>

<div id="page"> <!-- +Page container -->

	<?php echo get_template_part( 'partials/SearchPanel' ); ?>

	<?php echo get_template_part( 'partials/Navbar' ); ?>
