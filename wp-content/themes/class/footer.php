<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package CoursePress
 */
?>


</div><!-- #content -->

<div class="push"></div>
</div><!-- #page -->

<footer id="colophon" class="site-footer" role="contentinfo">

    <nav id="footer-navigation" class="footer-navigation wrap" role="navigation">
        <?php dynamic_sidebar( 'sidebar-2' ); ?>
          <?php //wp_nav_menu( array( 'theme_location' => 'secondary' ) ); ?>
    </nav><!-- #site-navigation -->
    <div style="clear:both;"> </div>
    <div class="wrapper wrap topborder">
                  	<div class="col-md-6">
                    	<p class=""><small><span class="current-year"><a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Лицензия Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a> </span> Творец TumanoV Alex</small></p>
                    </div>
                    <div class="col-md-6 text-right">
                      <nav id="footer-navigation" class="footer-navigation wrap" role="navigation">
                            <?php wp_nav_menu( array( 'theme_location' => 'secondary' ) ); ?>
                      </nav><!-- #site-navigation -->
                    </div>
                </div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>
