<?php
/**
 * Plugin Name: Module (9) - Widget Categories FIT TDC
 * Description: Widget hiển thị danh mục theo phong cách Khoa CNTT - Cao đẳng Công nghệ Thủ Đức (FIT TDC) với bullet vàng và gạch sọc chéo.
 * Version: 1.0.0
 * Author: Tran Cao Trong
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// MODULE (9) - CATEGORIES PHONG CÁCH KHOA CNTT - TDC (FIT TDC)
// -----------------------------------------------------------------------------
class TDC_FIT_Categories_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'tdc_fit_categories',
            'TDC (9) - Categories (Phong cách FIT TDC)',
            array( 'description' => 'Hiển thị danh mục phong cách FIT TDC với bullet vàng và đường kẻ mờ' )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Categories';
        $title = apply_filters( 'widget_title', $title );

        echo $args['before_widget'];
        ?>
        <div class="tdc-fit-categories-box">
            <h3 class="tdc-fit-categories-title"><?php echo esc_html( $title ); ?></h3>
            <div class="tdc-fit-title-stripe"></div>
            <ul class="tdc-fit-categories-list">
                <?php
                $categories = get_categories( array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false,
                ) );

                if ( ! empty( $categories ) ) {
                    foreach ( $categories as $cat ) {
                        $cat_link = esc_url( get_category_link( $cat->term_id ) );
                        echo '<li>';
                        echo '<span class="tdc-fit-bullet">&#8226;</span> ';
                        echo '<a href="' . $cat_link . '" class="tdc-fit-cat-link">' . esc_html( $cat->name ) . '</a>';
                        echo '</li>';
                    }
                } else {
                    echo '<li>Chưa có chuyên mục</li>';
                }
                ?>
            </ul>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Categories';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Tiêu đề:</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php 
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

// Đăng ký Widget
add_action( 'widgets_init', function() {
    register_widget( 'TDC_FIT_Categories_Widget' );
} );

// Shortcode: [tdc_categories title="Categories"]
add_shortcode( 'tdc_categories', function( $atts ) {
    $atts = shortcode_atts( array( 'title' => 'Categories' ), $atts );
    ob_start();
    the_widget( 'TDC_FIT_Categories_Widget', $atts );
    return ob_get_clean();
} );

// CSS Styling riêng cho Module 9
add_action( 'wp_head', function() {
    ?>
    <style id="tdc-module-9-css">
    .tdc-fit-categories-box,
    .widget_categories,
    .wp-block-categories {
        background: #ffffff;
        border: 1px solid #e9ecef;
        padding: 20px 24px;
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .tdc-fit-categories-title,
    .widget_categories .widget-title,
    .wp-block-categories h2 {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #222222 !important;
        margin: 0 0 10px 0 !important;
        text-transform: none;
        letter-spacing: -0.2px;
        position: relative;
    }
    .tdc-fit-title-stripe,
    .widget_categories .widget-title::after,
    .wp-block-categories h2::after {
        content: "";
        display: block;
        height: 8px;
        width: 100%;
        background: repeating-linear-gradient(
            -45deg,
            #d5d5d5,
            #d5d5d5 2px,
            transparent 2px,
            transparent 6px
        );
        margin-top: 8px;
        margin-bottom: 14px;
    }
    .tdc-fit-categories-list,
    .widget_categories ul,
    .wp-block-categories-list {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .tdc-fit-categories-list li,
    .widget_categories ul li,
    .wp-block-categories-list li {
        padding: 12px 0 !important;
        border-bottom: 1px solid #efefef !important;
        display: flex !important;
        align-items: center !important;
        margin: 0 !important;
    }
    .tdc-fit-categories-list li:last-child,
    .widget_categories ul li:last-child,
    .wp-block-categories-list li:last-child {
        border-bottom: none !important;
    }
    .tdc-fit-bullet,
    .widget_categories ul li::before,
    .wp-block-categories-list li::before {
        content: "•";
        color: #f5af02 !important;
        font-size: 22px !important;
        line-height: 1 !important;
        margin-right: 12px !important;
        display: inline-block !important;
    }
    .tdc-fit-cat-link,
    .widget_categories ul li a,
    .wp-block-categories-list li a {
        color: #337ab7 !important;
        text-decoration: none !important;
        font-size: 14.5px !important;
        font-weight: 500 !important;
        transition: color 0.2s !important;
    }
    .tdc-fit-cat-link:hover,
    .widget_categories ul li a:hover,
    .wp-block-categories-list li a:hover {
        color: #23527c !important;
        text-decoration: underline !important;
    }
    </style>
    <?php
}, 99 );
