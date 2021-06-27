<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
 * ------------------------------------------------------------------------------------------------
 * Header clone structure template
 * ------------------------------------------------------------------------------------------------
 */

$template = '
    <div class="whb-sticky-header whb-clone whb-main-header <%wrapperClasses%>">
        <div class="<%cloneClass%>">
            <div class="container">
                <div class="whb-flex-row whb-general-header-inner">
                    <div class="whb-column whb-col-left whb-visible-lg">
                        <%.site-logo%>
                    </div>
                    <div class="whb-column whb-col-center whb-visible-lg">
                        <%.wd-header-main-nav%>
                    </div>
                    <div class="whb-column whb-col-right whb-visible-lg">
                        <%.wd-header-my-account%>
                        <%.wd-header-search:not(.wd-header-search-mobile)%>
						<%.wd-header-wishlist%>
                        <%.wd-header-compare%>
                        <%.wd-header-cart%>
                        <%.wd-header-fs-nav%>
                    </div>
                    <%.whb-mobile-left%>
                    <%.whb-mobile-center%>
                    <%.whb-mobile-right%>
                </div>
            </div>
        </div>
    </div>
';

return apply_filters( 'woodmart_header_clone_template', $template );
