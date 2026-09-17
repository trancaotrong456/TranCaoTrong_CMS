<?php
/**
 * Plugin Name: Module (11) - Widget Archive VnExpress 2 Cột & Footer #3, #4
 * Description: Widget hiển thị bài viết lưu trữ dạng 2 cột chia đôi (Cột 1: 1-4, Cột 2: 5-8) với số thứ tự to đậm phong cách VnExpress Xem Nhiều và đăng ký Footer #3, Footer #4.
 * Version: 1.0.0
 * Author: Tran Cao Trong
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// KHÔI PHỤC CLASSIC WIDGETS & ĐĂNG KÝ FOOTER #3, FOOTER #4
// -----------------------------------------------------------------------------
add_filter( 'use_widgets_block_editor', '__return_false' );

function tdc_register_vne_sidebars() {
    $default_args = array(
        'before_widget' => '<div id="%1$s" class="widget tdc-footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    );

    register_sidebar( array_merge( $default_args, array(
        'name'          => 'Footer #3',
        'id'            => 'footer-3',
        'description'   => 'Khu vực hiển thị Widget tại Footer #3',
    ) ) );

    register_sidebar( array_merge( $default_args, array(
        'name'          => 'Footer #4',
        'id'            => 'footer-4',
        'description'   => 'Khu vực hiển thị Widget tại Footer #4',
    ) ) );
}
add_action( 'widgets_init', 'tdc_register_vne_sidebars', 20 );

// Hiển thị Footer #1, #2, #3, #4
function tdc_render_vne_footer_grid() {
    if ( is_active_sidebar( 'footer-widget-1' ) || is_active_sidebar( 'footer-widget-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) {
        echo '<div class="tdc-custom-footer-container"><div class="tdc-footer-grid">';
        if ( is_active_sidebar( 'footer-widget-1' ) ) {
            echo '<div class="tdc-footer-col tdc-footer-col-1">';
            dynamic_sidebar( 'footer-widget-1' );
            echo '</div>';
        }
        if ( is_active_sidebar( 'footer-widget-2' ) ) {
            echo '<div class="tdc-footer-col tdc-footer-col-2">';
            dynamic_sidebar( 'footer-widget-2' );
            echo '</div>';
        }
        if ( is_active_sidebar( 'footer-3' ) ) {
            echo '<div class="tdc-footer-col tdc-footer-col-3">';
            dynamic_sidebar( 'footer-3' );
            echo '</div>';
        }
        if ( is_active_sidebar( 'footer-4' ) ) {
            echo '<div class="tdc-footer-col tdc-footer-col-4">';
            dynamic_sidebar( 'footer-4' );
            echo '</div>';
        }
        echo '</div></div>';
    }
}
add_action( 'astra_footer_before', 'tdc_render_vne_footer_grid' );
add_action( 'wp_footer', function() {
    if ( ! did_action( 'astra_footer_before' ) && ( is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) ) {
        tdc_render_vne_footer_grid();
    }
}, 5 );


// -----------------------------------------------------------------------------
// MODULE (11) - ARCHIVE / TOP BÀI VIẾT 2 CỘT (PHONG CÁCH VNEXPRESS XEM NHIỀU)
// -----------------------------------------------------------------------------
class TDC_VnExpress_Archive_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'tdc_vnexpress_archive',
            'TDC (11) - Archive/Top bài viết 2 cột (VnExpress)',
            array( 'description' => 'Hiển thị bài viết lưu trữ dạng 2 cột chia đôi (Cột 1: 1-4, Cột 2: 5-8) với số thứ tự to đậm' )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Xem nhiều';
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 8;

        echo $args['before_widget'];
        ?>
        <div class="tdc-vnexpress-wrap">
            <?php if ( ! empty( $title ) ) : ?>
                <div class="tdc-vne-header">
                    <h3 class="tdc-vne-title"><?php echo esc_html( $title ); ?></h3>
                </div>
            <?php endif; ?>

            <div class="tdc-vne-grid">
                <?php
                $vne_query = new WP_Query( array(
                    'posts_per_page'      => $count,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 1,
                ) );

                $all_posts = $vne_query->posts;
                $half = ceil( count( $all_posts ) / 2 );
                $col1 = array_slice( $all_posts, 0, $half );
                $col2 = array_slice( $all_posts, $half );

                // Cột 1 (Số 1 -> 4)
                echo '<div class="tdc-vne-col">';
                $idx = 1;
                foreach ( $col1 as $p ) {
                    $permalink = esc_url( get_permalink( $p->ID ) );
                    $comment_count = get_comments_number( $p->ID );
                    echo '<div class="tdc-vne-item">';
                    echo '  <div class="tdc-vne-number">' . $idx . '</div>';
                    echo '  <div class="tdc-vne-title-wrap">';
                    echo '    <a href="' . $permalink . '" class="tdc-vne-item-title">' . esc_html( get_the_title( $p->ID ) ) . '</a>';
                    if ( $comment_count > 0 ) {
                        echo ' <span class="tdc-vne-comments-count">&#128172; ' . $comment_count . '</span>';
                    }
                    echo '  </div>';
                    echo '</div>';
                    $idx++;
                }
                echo '</div>';

                // Cột 2 (Số 5 -> 8)
                echo '<div class="tdc-vne-col">';
                foreach ( $col2 as $p ) {
                    $permalink = esc_url( get_permalink( $p->ID ) );
                    $comment_count = get_comments_number( $p->ID );
                    echo '<div class="tdc-vne-item">';
                    echo '  <div class="tdc-vne-number">' . $idx . '</div>';
                    echo '  <div class="tdc-vne-title-wrap">';
                    echo '    <a href="' . $permalink . '" class="tdc-vne-item-title">' . esc_html( get_the_title( $p->ID ) ) . '</a>';
                    if ( $comment_count > 0 ) {
                        echo ' <span class="tdc-vne-comments-count">&#128172; ' . $comment_count . '</span>';
                    }
                    echo '  </div>';
                    echo '</div>';
                    $idx++;
                }
                echo '</div>';
                ?>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Xem nhiều';
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 8;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Tiêu đề (mặc định: Xem nhiều):</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">Số lượng bài viết (mặc định 8 bài - 2 cột):</label> 
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="2" max="20" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <?php 
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? absint( $new_instance['count'] ) : 8;
        return $instance;
    }
}

// Đăng ký Widget
add_action( 'widgets_init', function() {
    register_widget( 'TDC_VnExpress_Archive_Widget' );
} );

// Shortcode: [tdc_vnexpress_archive count="8" title="Xem nhiều"]
add_shortcode( 'tdc_vnexpress_archive', function( $atts ) {
    $atts = shortcode_atts( array( 'count' => 8, 'title' => 'Xem nhiều' ), $atts );
    ob_start();
    the_widget( 'TDC_VnExpress_Archive_Widget', $atts );
    return ob_get_clean();
} );

// CSS Styling riêng cho Module 11 & Footer Grid
add_action( 'wp_head', function() {
    ?>
    <style id="tdc-module-11-css">
    /* CSS CHO FOOTER GRID 4 CỘT */
    .tdc-custom-footer-container {
        width: 100%;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 40px 20px;
        margin-top: 40px;
    }
    .tdc-footer-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    .tdc-footer-col {
        min-width: 0;
    }
    .tdc-footer-col .widget-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 18px;
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 8px;
        display: inline-block;
    }

    /* CSS MODULE (11) - ARCHIVE 2 CỘT VNEXPRESS */
    .tdc-vnexpress-wrap {
        background: #ffffff;
        padding: 20px 24px;
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .tdc-vne-header {
        border-bottom: 1px solid #e5e5e5;
        margin-bottom: 20px;
        padding-bottom: 8px;
        position: relative;
    }
    .tdc-vne-title {
        font-size: 20px;
        font-weight: 700;
        color: #222222;
        margin: 0;
        display: inline-block;
        position: relative;
    }
    .tdc-vne-title::after {
        content: "";
        position: absolute;
        bottom: -9px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #b52a1d;
    }
    .tdc-vne-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    @media (max-width: 768px) {
        .tdc-vne-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
    .tdc-vne-col {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .tdc-vne-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f2f2f2;
    }
    .tdc-vne-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .tdc-vne-number {
        font-size: 32px;
        font-weight: 700;
        font-family: Georgia, 'Times New Roman', Times, serif;
        color: #222222;
        line-height: 1;
        flex: 0 0 28px;
    }
    .tdc-vne-title-wrap {
        flex: 1;
    }
    .tdc-vne-item-title {
        font-size: 14.5px;
        line-height: 1.45;
        color: #222222 !important;
        text-decoration: none !important;
        font-weight: 500;
        display: inline;
        transition: color 0.2s;
    }
    .tdc-vne-item-title:hover {
        color: #076db6 !important;
    }
    .tdc-vne-comments-count {
        font-size: 12px;
        color: #888888;
        margin-left: 6px;
        white-space: nowrap;
    }
    </style>
    <?php
}, 99 );
