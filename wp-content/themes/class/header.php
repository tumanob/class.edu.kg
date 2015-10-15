<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package CoursePress
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>
        <?php include( get_template_directory() . '/inc/custom-colors.php' ); ?>
    </head>

    <body <?php body_class('cp-wrap'); ?>>
        <div id="page" class="hfeed site">
            <?php do_action('before'); ?>

  <nav class="navbar navbar-default ">

  <div class="container-fluid wrap">
    <!-- Brand and toggle get grouped for better mobile display -->

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
          <?php
          //$logo_image_url = get_theme_mod('coursepress_logo'); //get_stylesheet_directory_uri() . '/images/logo-default.png'
          ?>
          <h3 class="site-title">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php if ( isset($logo_image_url) && $logo_image_url != '' ) { ?><img id="coursepress_logo" src='<?php echo $logo_image_url; ?>' alt='<?php bloginfo('name'); ?>' border='0' /><?php
                  } else {
                      echo get_bloginfo('name');
                  }
          ?></a></h3>
        </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right main-navigation">
            <?php wp_nav_menu(array( 'theme_location' => 'primary' )); ?>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

            <div id="content" class="site-content">
              <div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
                  <?php if(function_exists('bcn_display'))
                  {
                      bcn_display();
                  }?>
              </div>
