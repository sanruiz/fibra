<?php
/**
 *
 *
 * @package Soma
 * Single Team Member Partial
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$info  = get_field( 'team_member_info' );
$image = get_the_post_thumbnail_url()
?>

<section class="single-team-members-6a9625">
	<div class="container">
		<div class="content">
			<!-- <div id="magic-indexer" class="left-text desk">
				<div class="featured-text">
					<div class="mobile-logo">
						<svg width="66px" height="68px" viewBox="0 0 106 109" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-79.000000, -668.000000)">
									<g transform="translate(79.000000, 668.269531)">
										<polygon fill="#171717" points="106 90.384203 90.0913455 43.7074192 75.1941215 0 30.5243463 0 0 89.5594954 0 107.752066 19.9512483 107.752066 47.9584883 24.654692 57.7589368 24.654692 85.7651341 107.752066 106 107.752066"></polygon>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<h3><?php echo esc_html( $info['featured_text'] ); ?></h3>
				</div>
			</div> -->
			<div class="title">
				<h3 class="member-name"><?php echo esc_html( get_the_title() ); ?></h3>
				<h3 class="member-title"><?php echo esc_html( $info['title'] ); ?></h3>
				<svg width="106px" height="109px" viewBox="0 0 106 109" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<g transform="translate(-79.000000, -668.000000)">
							<g transform="translate(79.000000, 668.269531)">
								<polygon fill="#171717" points="106 90.384203 90.0913455 43.7074192 75.1941215 0 30.5243463 0 0 89.5594954 0 107.752066 19.9512483 107.752066 47.9584883 24.654692 57.7589368 24.654692 85.7651341 107.752066 106 107.752066"></polygon>
							</g>
						</g>
					</g>
				</svg>
			</div>
			<div class="featured-image">
				<?php if ( $image ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" alt="Featured image">
				<?php endif; ?>
			</div>
			<div class="featured-text movil">
				<div class="mobile-logo">
					<svg width="66px" height="68px" viewBox="0 0 106 109" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<g transform="translate(-79.000000, -668.000000)">
								<g transform="translate(79.000000, 668.269531)">
									<polygon fill="#171717" points="106 90.384203 90.0913455 43.7074192 75.1941215 0 30.5243463 0 0 89.5594954 0 107.752066 19.9512483 107.752066 47.9584883 24.654692 57.7589368 24.654692 85.7651341 107.752066 106 107.752066"></polygon>
								</g>
							</g>
						</g>
					</svg>
				</div>
				<h3><?php echo $info['featured_text']; ?></h3>
			</div>
			<div class="body">
				<div class="body-content">
					<?php echo $info['body']; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	// var windowHh = $('.single-team-members-6a9625').height();
	// var windowHh = $('.body').height();
	// var windowH = $(document).height();
	
	// var boxH = ($('#magic-indexer').height());
   
	// console.log(boxH); 
	// console.log(windowHh); 
	// console.log(($('#magic-indexer').height())); 

	// if (($('#magic-indexer').height())< windowHh) {
	   
	//     $(window).scroll(function (event) {
	//         var scroll = $(window).scrollTop();
	//         console.log(scroll);
	//         // if (scroll > 780 && scroll < (windowHh - boxH))
	//         if (scroll > 785 && scroll < (windowHh + 200)) {
	//             $('#magic-indexer').addClass('sticky-text'); ;
	//             $('#magic-indexer').removeClass('sticky-text-absoluted'); 
	//         }
	//         else if (scroll > (windowHh + 200)) {
	//             $('#magic-indexer').addClass('sticky-text-absoluted',10000); 
	//             $('#magic-indexer').removeClass('sticky-text'); 
	//         }
	//         else{
	//             $('#magic-indexer').removeClass('sticky-text'); 
	//         }
	//     });
	// }
	// else{
	//     $('.single-team-members-6a9625').css("height", ($('#magic-indexer').height() + 550));
	// }

	function topVerify(data) {
		return (
			data.bodyContent > (data.featuredText + data.navbar) && 
			($(window).scrollTop() + data.navbar + data.margin) > data.featuredTextOffset &&
			$(window).width() > 767 && 
			!bottomVerify(data)
		);
	}

	function bottomVerify(data) {
		return (
			data.bodyContent > (data.featuredText + data.navbar) && 
			(data.featuredText + data.navbar + data.margin + $(window).scrollTop() + 140) > data.footerOffset && 
			$(window).width() > 767
		);
	}

	$(window).on('scroll', function() {
		let data = {
			footerOffset: $('.footer-partial-c90350').offset().top,
			bodyContent: $('.body-content').innerHeight(),
			featuredText: $('.featured-text h3').innerHeight(),
			featuredTextOffset: $('.featured-text').offset().top,
			navbar: $('.navbar-partial-df27ae').innerHeight(),
			margin: 60
		};
		if(topVerify(data)) {
			$('.featured-text h3').css({
				'position': 'fixed',
				'top': data.navbar + data.margin,
				'bottom': 'unset',
				'max-width': (($('.featured-text').width() - 15) > 480) ? '480px' : ($('.featured-text').width() - 15)
			});
		}
		if(bottomVerify(data)) {
			$('.featured-text h3').css({
				'position': 'absolute',
				'top': 'unset',
				'bottom': '0'
			});
		}
		if(!topVerify(data) && !bottomVerify(data)) {
			$('.featured-text h3').attr('style', '');
		}
	});
		
</script>
