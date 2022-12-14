<?php
add_action( 'after_setup_theme', 'idt_setup' );
function idt_setup() {
    load_theme_textdomain( 'industrydivetest', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
    add_theme_support( 'woocommerce' );
    global $content_width;
    if ( !isset( $content_width ) ) { $content_width = 1920; }
    register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'idt' ) ) );
}
add_action( 'admin_notices', 'idt_notice' );
function idt_notice() {
    $user_id = get_current_user_id();
    $admin_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $param = ( count( $_GET ) ) ? '&' : '?';
    if ( !get_user_meta( $user_id, 'idt_notice_dismissed_8' ) && current_user_can( 'manage_options' ) )
        echo '<div class="notice notice-info"><p><a href="' . esc_url( $admin_url ), esc_html( $param ) . 'dismiss" class="alignright" style="text-decoration:none"><big>' . esc_html__( 'Ⓧ', 'idt' ) . '</big></a>' . wp_kses_post( __( '<big><strong>📝 Thank you for using idt!</strong></big>', 'idt' ) ) . '<br /><br /><a href="https://wordpress.org/support/theme/idt/reviews/#new-post" class="button-primary" target="_blank">' . esc_html__( 'Review', 'idt' ) . '</a> <a href="https://github.com/tidythemes/idt/issues" class="button-primary" target="_blank">' . esc_html__( 'Feature Requests & Support', 'idt' ) . '</a> <a href="https://calmestghost.com/donate" class="button-primary" target="_blank">' . esc_html__( 'Donate', 'idt' ) . '</a></p></div>';
}
add_action( 'admin_init', 'idt_notice_dismissed' );
function idt_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['dismiss'] ) )
        add_user_meta( $user_id, 'idt_notice_dismissed_8', 'true', true );
}
add_action( 'wp_enqueue_scripts', 'idt_enqueue' );
function idt_enqueue() {
    wp_enqueue_style( 'idt-style', get_stylesheet_uri() );
    wp_enqueue_style( 'idt-theme-style', get_stylesheet_directory_uri() . '/assets/css/theme.css' );
    wp_enqueue_style( 'jquery-modal', '//cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css',false,'0.9.1' );


    wp_enqueue_script( 'jquery-modal', '//cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js',array('jquery'),'0.9.1',true );
    wp_enqueue_script( 'idt-script', get_stylesheet_directory_uri() . '/assets/js/script.js',array('jquery', 'jquery-modal'),'1.0.0',true);
    wp_enqueue_script( 'fontawesome',  '//kit.fontawesome.com/2a0c8f31a4.js',array(),'5.0.0',true);
    wp_enqueue_script( 'masonary',  'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js',array(),'4.2.2',true);
    wp_enqueue_script( 'jquery-imagesloaded',  '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js',array(),'3.2.0',true);

    wp_enqueue_script( 'jquery' );

}
add_action( 'wp_footer', 'idt_footer' );
function idt_footer() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            var deviceAgent = navigator.userAgent.toLowerCase();
            if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
                $("html").addClass("ios");
                $("html").addClass("mobile");
            }
            if (deviceAgent.match(/(Android)/)) {
                $("html").addClass("android");
                $("html").addClass("mobile");
            }
            if (navigator.userAgent.search("MSIE") >= 0) {
                $("html").addClass("ie");
            }
            else if (navigator.userAgent.search("Chrome") >= 0) {
                $("html").addClass("chrome");
            }
            else if (navigator.userAgent.search("Firefox") >= 0) {
                $("html").addClass("firefox");
            }
            else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
                $("html").addClass("safari");
            }
            else if (navigator.userAgent.search("Opera") >= 0) {
                $("html").addClass("opera");
            }
        });
    </script>
    <?php
}
add_filter( 'document_title_separator', 'idt_document_title_separator' );
function idt_document_title_separator( $sep ) {
    $sep = esc_html( '|' );
    return $sep;
}
add_filter( 'the_title', 'idt_title' );
function idt_title( $title ) {
    if ( $title == '' ) {
        return esc_html( '...' );
    } else {
        return wp_kses_post( $title );
    }
}
function idt_schema_type() {
    $schema = 'https://schema.org/';
    if ( is_single() ) {
        $type = "Article";
    } elseif ( is_author() ) {
        $type = 'ProfilePage';
    } elseif ( is_search() ) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }
    echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'idt_schema_url', 10 );
function idt_schema_url( $atts ) {
    $atts['itemprop'] = 'url';
    return $atts;
}
if ( !function_exists( 'idt_wp_body_open' ) ) {
    function idt_wp_body_open() {
        do_action( 'wp_body_open' );
    }
}
add_action( 'wp_body_open', 'idt_skip_link', 5 );
function idt_skip_link() {
    echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'idt' ) . '</a>';
}
add_filter( 'the_content_more_link', 'idt_read_more_link' );
function idt_read_more_link() {
    if ( !is_admin() ) {
        return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'idt' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
    }
}
add_filter( 'excerpt_more', 'idt_excerpt_read_more_link' );
function idt_excerpt_read_more_link( $more ) {
    if ( !is_admin() ) {
        global $post;
        return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'idt' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
    }
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'idt_image_insert_override' );
function idt_image_insert_override( $sizes ) {
    unset( $sizes['medium_large'] );
    unset( $sizes['1536x1536'] );
    unset( $sizes['2048x2048'] );
    return $sizes;
}
add_action( 'widgets_init', 'idt_widgets_init' );
function idt_widgets_init() {
    register_sidebar( array(
        'name' => esc_html__( 'Sidebar Widget Area', 'idt' ),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'wp_head', 'idt_pingback_header' );
function idt_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'comment_form_before', 'idt_enqueue_comment_reply_script' );
function idt_enqueue_comment_reply_script() {
    if ( get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
function idt_custom_pings( $comment ) {
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
    <?php
}
add_filter( 'get_comments_number', 'idt_comment_count', 0 );
function idt_comment_count( $count ) {
    if ( !is_admin() ) {
        global $id;
        $get_comments = get_comments( 'status=approve&post_id=' . $id );
        $comments_by_type = separate_comments( $get_comments );
        return count( $comments_by_type['comment'] );
    } else {
        return $count;
    }
}
function custom_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
          <div class="input-container">
            <i class="fas fa-search"></i>
            <input class="input-field" type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search"/>
          </div>
      </form>';

    return $form;
}
add_filter( 'get_search_form', 'custom_search_form', 40 );

function prefix_estimated_reading_time($post_id) {
    // get the content
    $the_content = get_post($post_id)->post_content;
    // count the number of words
    $words = str_word_count( strip_tags( $the_content ) );
    // rounding off and deviding per 200 words per minute
    $minute = floor( $words / 200 );
    // rounding off to get the seconds
    $second = floor( $words % 200 / ( 200 / 60 ) );
    // calculate the amount of time needed to read
    $estimate = $minute . ' minute' . ( $minute == 1 ? '' : 's' ) . ', ' . $second . ' second' . ( $second == 1 ? '' : 's' );
    // create output
    $output =  $estimate . ' read';
    // return the estimate
    return $output;
}
