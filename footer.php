<?php wp_footer(); ?>
<?php if ( preg_match("/(.*.local|.*.loc|localhost:.*)/i", $_SERVER['HTTP_HOST']) ) : ?>
	<!-- HMR Reloader -->
	<script id="__bs_script__">
		//<![CDATA[
      document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.26.5'><\/script>".replace("HOST", location.hostname));
		//]]>
	</script>
<?php endif; ?>
<!-- Scripts -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/scripts.js"></script> <!-- Custom scripts -->
</body>

</html>
