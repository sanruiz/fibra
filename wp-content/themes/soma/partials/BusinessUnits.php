<?php
/**
 * Block Partial: BusinessUnits
 *
 * Displays a grid of business unit pages with links.
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'business_units_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('BusinessUnits')
 *
 * Example Usage:
 * <code>
 * $counter = get_query_var('soma_block_counter');
 * $content = get_query_var('soma_block_content');
 * $layout  = get_query_var('soma_block_layout');
 * </code>
 *
 * @see \Soma\PageBuilder\BlockRenderer
 * @see \Soma\PageBuilder\BlockRegistry
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get block data from query vars (v3.0+)
$block_counter = get_query_var( 'soma_block_counter' );
$block_content = get_query_var( 'soma_block_content' );
$block_layout  = get_query_var( 'soma_block_layout' );

$businessunits = get_posts(
	[
		'post_type'  => 'page',
		'fields'     => 'ids',
		'nopaging'   => true,
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'templates/business-unit-template.php',
	]
);

$svg = '
<svg width="151px" height="37px" viewBox="0 0 151 37" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <polygon id="path-1" points="0.247773285 0.183428928 34.7473101 0.183428928 34.7473101 35.2565333 0.247773285 35.2565333"></polygon>
        <polygon id="path-3" points="0.259108209 0.337706062 0.687811365 0.337706062 0.687811365 0.339806708 0.259108209 0.339806708"></polygon>
    </defs>
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="01_SOMA---Home" transform="translate(-79.000000, -56.000000)">
            <g id="Group-13" transform="translate(79.000000, 56.250000)">
                <path d="M60.9196827,18.4248844 C60.9196827,12.031173 57.5066489,7.41745183 51.593484,7.41745183 C45.6809978,7.41745183 42.2683034,12.031173 42.2683034,18.4248844 C42.2683034,24.9621489 45.6809978,29.674287 51.593484,29.674287 C57.5066489,29.674287 60.9196827,24.9621489 60.9196827,18.4248844 M33.6164309,18.4248844 C33.6164309,7.80229625 40.9220267,0.255069718 51.593484,0.255069718 C62.217769,0.255069718 69.6200849,7.6573857 69.6200849,18.4248844 C69.6200849,29.3372936 62.5537442,36.6924373 51.593484,36.6924373 C40.6820929,36.6924373 33.6164309,29.3372936 33.6164309,18.4248844" id="Fill-1" fill="#FFFFFF"></path>
                <path d="M72.788807,0.928174133 L85.8646934,0.928174133 L89.5180003,14.3396965 C90.527623,18.1847469 91.632269,22.2707493 92.9310341,27.4627552 L93.0745871,27.4627552 C94.4680361,21.9340953 95.5261886,18.0890448 96.6797037,13.7142395 L99.996696,0.928174133 L113.024053,0.928174133 L113.024053,36.0185863 L104.99628,36.0185863 L104.99628,18.3296574 C104.99628,14.5314399 105.09266,11.3590188 105.140172,7.94632444 L104.948089,7.94632444 C104.322632,10.7817522 103.45792,14.1476136 102.545018,17.4153974 L97.2084406,36.0185863 L88.4601873,36.0185863 L83.0285869,17.5596293 C82.0661365,14.2911667 81.3456562,11.1187456 80.7205385,8.04236585 L80.5277769,8.04236585 C80.6248365,10.8306213 80.6730269,13.9551914 80.6730269,17.3685645 L80.6730269,36.0185863 L72.788807,36.0185863 L72.788807,0.928174133 Z" id="Fill-3" fill="#FFFFFF"></path>
                <g id="Group-7" transform="translate(116.064188, 0.771725)">
                    <mask id="mask-2" fill="white">
                        <use xlink:href="#path-1"></use>
                    </mask>
                    <g id="Clip-6"></g>
                    <polygon id="Fill-5" fill="#FFFFFF" mask="url(#mask-2)" points="34.7473441 29.603325 29.5695917 14.4101155 24.7210272 0.183428928 10.1824605 0.183428928 0.247773285 29.3348841 0.247773285 35.2565333 6.74125916 35.2565333 15.8567097 8.20848697 19.0464386 8.20848697 28.1615498 35.2565333 34.7473441 35.2565333"></polygon>
                </g>
                <g id="Group-10" transform="translate(14.932235, 36.405467)">
                    <mask id="mask-4" fill="white">
                        <use xlink:href="#path-3"></use>
                    </mask>
                    <g id="Clip-9"></g>
                    <path d="M0.438734831,0.339849578 C0.414298751,0.339420875 0.392863593,0.337706062 0.368427514,0.337706062 C0.331987745,0.338134765 0.296405383,0.339420875 0.259108209,0.339849578 L0.438734831,0.339849578 Z" id="Fill-8" fill="#171717" mask="url(#mask-4)"></path>
                </g>
                <path d="M26.6662562,17.0604514 C23.53422,15.8421168 20.8952869,15.3188099 16.8734252,14.8576075 C10.1953226,14.0909729 9.58479782,12.0924291 9.5369468,10.7332564 C9.48977451,8.3430807 11.2253074,7.07791318 15.2128928,7.07791318 C19.8602115,7.07791318 23.4592194,8.26366836 26.4493994,10.3728465 L30.9579162,4.60866455 C29.2020211,3.20265891 26.581414,2.01588562 24.3510811,1.21938665 C22.1204088,0.422208936 18.7721946,3.3936897e-05 15.4019213,3.3936897e-05 C7.35480432,3.3936897e-05 1.08903503,4.1488196 1.08903503,10.8740945 C1.08903503,15.1430168 2.94877698,17.8277647 5.93013338,19.2629561 C8.71906758,20.6054997 11.4615082,20.9180585 15.4019213,21.6066382 C21.3812632,22.6512159 23.3445127,23.0856081 23.3445127,25.8243157 C23.3445127,28.6403994 20.8603319,30.042672 15.4019213,30.042672 C12.9815418,29.9954997 11.1231574,29.5268312 9.08219238,28.6370057 C7.16747265,27.80114 5.55037951,26.8397077 4.25975931,25.6797445 L0,31.1160961 C1.48949041,32.5994778 3.42253606,33.8721115 5.80762119,34.8698563 C8.5941798,36.035928 11.4839066,36.6905708 15.3941159,36.7428336 C20.8545626,36.6854802 24.924954,35.6086625 27.6154712,33.5106835 C30.3205813,31.4018447 31.6974012,28.6370057 31.6974012,25.2151484 C31.4595035,20.9502986 29.7989712,18.2322925 26.6662562,17.0604514" id="Fill-11" fill="#FFFFFF"></path>
            </g>
        </g>
    </g>
</svg>
';
?>

<?php if ( $businessunits ) : ?>
<section class="businessunits-partial-cea85c">
	<?php $total_pages = ( count( $businessunits ) <= 8 ) ? count( $businessunits ) : 8; ?>
	<div class="container">
		<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
			<div class="title"><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></div>
		<?php endif; ?>
	</div>
	<?php if ( $total_pages >= 3 ) : ?>
		<div class="content" data-total="<?php echo esc_attr( $total_pages ); ?>">
			<?php for ( $i = 0; $i < $total_pages; $i++ ) : ?>
				<?php if ( $businessunits[ $i ] ) : ?>
					<div class="item item-num-<?php echo esc_attr( $businessunits[ $i ] ); ?>">
						<?php $businessunit_info = get_field( 'business_unit_data', $businessunits[ $i ] ); ?>
						<style>
							.item-num-<?php echo esc_attr( $businessunits[ $i ] ); ?> a:hover {
								background-color: <?php echo esc_attr( $businessunit_info['color'] ); ?>;
								
							}
						</style> 
						<div class="cta desk">
							<a href="<?php echo esc_url( get_the_permalink( $businessunits[ $i ] ) ); ?>">
								<div class="logo">
								<?php echo wp_kses_post( $svg ); ?>
									<span style="color: <?php echo esc_attr( $businessunit_info['color'] ); ?>"><?php echo esc_html( $businessunit_info['label'] ); ?></span>
								</div>
							</a>
						</div>
						<?php if ( $businessunit_info['image_cover'] ) : ?>
							<div class="image">
								<img src="<?php echo esc_url( $businessunit_info['image_cover']['url'] ); ?>" alt="<?php echo esc_attr( $businessunit_info['image_cover']['alt'] ); ?>">
							</div>
						<?php endif; ?>
						<div class="cta mobile">
							<style>
								@media (max-width: 991px){ .item-num-<?php echo esc_attr( $businessunits[ $i ] ); ?> a{
									background-color: <?php echo esc_attr( $businessunit_info['color'] ); ?>;
								}}
							</style> 
							<a href="<?php echo esc_url( get_the_permalink( $businessunits[ $i ] ) ); ?>">
								<div class="logo">
									<?php echo wp_kses_post( $svg ); ?>
									<span style="color: <?php echo esc_attr( $businessunit_info['color'] ); ?>"><?php echo esc_html( $businessunit_info['label'] ); ?></span>
								</div>
								<svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="39.124" height="38" viewBox="0 0 39.124 38">
									<g transform="translate(1.774)">
										<g transform="translate(-0.857 37) rotate(-90)">
											<path d="M.7.083V35.724" transform="translate(17.399 0)" fill="none" stroke="#fff" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
											<path d="M.7.083V35.724" transform="translate(17.399 0)" fill="none"/>
											<path d="M.892,35.2,0,34.309,16.708,17.6,0,.892.892,0l17.6,17.6Z" transform="translate(35.7 19.007) rotate(90)" fill="#fff" stroke="#fff" stroke-miterlimit="10" stroke-width="1"/>
											<path d="M.892,35.2,0,34.309,16.708,17.6,0,.892.892,0l17.6,17.6Z" transform="translate(35.7 19.007) rotate(90)" fill="none"/>
										</g>
									</g>
								</svg>
							</a>
						</div>
					</div>
				<?php else : ?>
					<div class="item empty-item"></div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
</section>
<?php endif; ?>