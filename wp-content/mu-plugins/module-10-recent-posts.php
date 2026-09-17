<?php
/**
 * Plugin Name: Module (10) - Widget 10 Recent Posts FIT TDC
 * Description: Widget hiển thị danh sách bài viết mới nhất phong cách Khoa CNTT - Cao đẳng Công nghệ Thủ Đức (FIT TDC) với hộp ngày tháng và nút xem tất cả tin tức.
 * Version: 1.0.0
 * Author: Tran Cao Trong
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// MODULE (10) - 10 RECENT POSTS PHONG CÁCH KHOA CNTT - TDC (FIT TDC)
// -----------------------------------------------------------------------------
class TDC_FIT_Recent_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'tdc_fit_recent_posts',
            'TDC (10) - Recent Posts (Phong cách FIT TDC)',
            array( 'description' => 'Hiển thị 10 bài viết mới nhất với nền xanh ngọc, hiển thị ngày/tháng/năm và nút xem tất cả' )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Bài viết mới nhất';
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 10;
        $more_link = ! empty( $instance['more_link'] ) ? esc_url( $instance['more_link'] ) : home_url( '/' );

        echo $args['before_widget'];
        ?>
        <div class="tdc-fit-recent-box">
            <?php if ( ! empty( $title ) ) : ?>
                <div class="tdc-fit-recent-header"><?php echo esc_html( $title ); ?></div>
            <?php endif; ?>

            <div class="tdc-fit-recent-list">
                <?php
                $recent_query = new WP_Query( array(
                    'posts_per_page'      => $count,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 1,
                ) );

                if ( $recent_query->have_posts() ) :
                    while ( $recent_query->have_posts() ) : $recent_query->the_post();
                        $day   = get_the_date( 'd' );
                        $month = get_the_date( 'm' );
                        $year  = get_the_date( 'y' );
                        ?>
                        <div class="tdc-fit-post-item">
                            <div class="tdc-fit-date-box">
                                <div class="tdc-fit-date-top">
                                    <span class="tdc-fit-day"><?php echo esc_html( $day ); ?></span>
                                    <span class="tdc-fit-dash">—</span>
                                    <span class="tdc-fit-year"><?php echo esc_html( $year ); ?></span>
                                </div>
                                <div class="tdc-fit-date-bottom">
                                    <span class="tdc-fit-month"><?php echo esc_html( $month ); ?></span>
                                </div>
                            </div>
                            <div class="tdc-fit-title-wrap">
                                <a href="<?php the_permalink(); ?>" class="tdc-fit-post-title" title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="color:#fff; padding:10px;">Chưa có bài viết nào.</p>';
                endif;
                ?>
            </div>

            <div class="tdc-fit-recent-footer">
                <a href="<?php echo $more_link; ?>" class="tdc-fit-all-news-btn">XEM TẤT CẢ TIN TỨC</a>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title     = ! empty( $instance['title'] ) ? $instance['title'] : 'Bài viết mới nhất';
        $count     = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 10;
        $more_link = ! empty( $instance['more_link'] ) ? $instance['more_link'] : home_url( '/' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Tiêu đề:</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">Số lượng bài viết (mặc định 10):</label> 
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'more_link' ) ); ?>">Đường dẫn "Xem tất cả tin tức":</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_link' ) ); ?>" type="text" value="<?php echo esc_attr( $more_link ); ?>">
        </p>
        <?php 
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']     = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count']     = ( ! empty( $new_instance['count'] ) ) ? absint( $new_instance['count'] ) : 10;
        $instance['more_link'] = ( ! empty( $new_instance['more_link'] ) ) ? esc_url_raw( $new_instance['more_link'] ) : '';
        return $instance;
    }
}

// Đăng ký Widget
add_action( 'widgets_init', function() {
    register_widget( 'TDC_FIT_Recent_Posts_Widget' );
} );

// Shortcode: [tdc_recent_posts count="10" title="Bài viết mới nhất"]
add_shortcode( 'tdc_recent_posts', function( $atts ) {
    $atts = shortcode_atts( array( 'count' => 10, 'title' => 'Bài viết mới nhất', 'more_link' => home_url('/') ), $atts );
    ob_start();
    the_widget( 'TDC_FIT_Recent_Posts_Widget', $atts );
    return ob_get_clean();
} );

// CSS Styling riêng cho Module 10
add_action( 'wp_head', function() {
    ?>
    <style id="tdc-module-10-css">
    .tdc-fit-recent-box {
        background-color: #42b4b7;
        border-radius: 4px;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .tdc-fit-recent-header {
        font-size: 17px;
        font-weight: 700;
        color: #205c5e;
        background-color: #d1efef;
        padding: 12px 18px;
    }
    .tdc-fit-recent-list {
        padding: 16px 20px;
    }
    .tdc-fit-post-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.25);
    }
    .tdc-fit-post-item:last-child {
        border-bottom: none;
    }
    .tdc-fit-date-box {
        flex: 0 0 52px;
        text-align: center;
        margin-right: 18px;
        font-weight: 700;
        line-height: 1.1;
        color: #ffffff;
    }
    .tdc-fit-date-top {
        font-size: 13px;
        border-bottom: 1px solid rgba(255,255,255,0.7);
        padding-bottom: 2px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2px;
    }
    .tdc-fit-day {
        font-size: 14px;
    }
    .tdc-fit-dash {
        font-size: 10px;
        opacity: 0.8;
    }
    .tdc-fit-year {
        font-size: 11px;
        opacity: 0.9;
    }
    .tdc-fit-date-bottom {
        padding-top: 2px;
    }
    .tdc-fit-month {
        font-size: 13px;
    }
    .tdc-fit-title-wrap {
        flex: 1;
        min-width: 0;
    }
    .tdc-fit-post-title {
        color: #ffffff !important;
        text-decoration: none !important;
        font-size: 14.5px;
        line-height: 1.4;
        display: block;
        transition: opacity 0.2s;
    }
    .tdc-fit-post-title:hover {
        opacity: 0.85;
        text-decoration: underline !important;
    }
    .tdc-fit-recent-footer {
        background-color: #3aa6a9;
        text-align: center;
        padding: 12px 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
    }
    .tdc-fit-all-news-btn {
        color: #ffffff !important;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-decoration: none !important;
        display: block;
        transition: opacity 0.2s;
    }
    .tdc-fit-all-news-btn:hover {
        opacity: 0.85;
    }
    </style>
    <?php
}, 99 );
