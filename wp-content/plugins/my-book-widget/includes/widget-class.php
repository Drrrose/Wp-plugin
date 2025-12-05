<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class My_Book_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'my_book_widget';
	}

	public function get_title() {
		return esc_html__( 'Book Grid', 'my-book-widget' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		return [ 'my-book-widget-js' ];
	}

	public function get_style_depends() {
		return [ 'my-book-widget-css' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'my-book-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'file_source',
			[
				'label' => esc_html__( 'Book Data File (CSV/JSON)', 'my-book-widget' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'description' => esc_html__( 'Upload a CSV or JSON file containing book data.', 'my-book-widget' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$file_url = isset( $settings['file_source']['url'] ) ? $settings['file_source']['url'] : '';

		if ( empty( $file_url ) ) {
			echo '<div class="mbw-error">' . esc_html__( 'Please upload a data file.', 'my-book-widget' ) . '</div>';
			return;
		}

		$books = mbw_get_books_from_url( $file_url );

		if ( is_wp_error( $books ) ) {
			echo '<div class="mbw-error">' . esc_html( $books->get_error_message() ) . '</div>';
			return;
		}

		if ( empty( $books ) ) {
			echo '<div class="mbw-info">' . esc_html__( 'No books found in the file.', 'my-book-widget' ) . '</div>';
			return;
		}

		?>
		<div class="mbw-book-widget-container">
			
			<!-- Controls -->
			<div class="mbw-controls-wrapper">
				<div class="mbw-search-wrapper">
					<input type="text" class="mbw-search-input" placeholder="<?php echo esc_attr__( 'Search by Name or Author...', 'my-book-widget' ); ?>">
				</div>
				<div class="mbw-sort-wrapper">
					<label for="mbw-sort-select"><?php echo esc_html__( 'Sort by:', 'my-book-widget' ); ?></label>
					<select class="mbw-sort-select">
						<option value="default" disabled selected><?php echo esc_html__( 'Select', 'my-book-widget' ); ?></option>
						<option value="name"><?php echo esc_html__( 'Name', 'my-book-widget' ); ?></option>
						<option value="author"><?php echo esc_html__( 'Author', 'my-book-widget' ); ?></option>
						<option value="date"><?php echo esc_html__( 'Release Date', 'my-book-widget' ); ?></option>
					</select>
				</div>
			</div>

			<!-- Grid -->
			<div class="mbw-book-grid">
				<?php foreach ( $books as $book ) : ?>
					<div class="mbw-book-item" 
						data-name="<?php echo esc_attr( strtolower( $book['book_name'] ) ); ?>" 
						data-author="<?php echo esc_attr( strtolower( $book['author_name'] ) ); ?>" 
						data-date="<?php echo esc_attr( $book['release_date'] ); ?>">
						
						<div class="mbw-book-cover">
							<?php if ( ! empty( $book['cover_image'] ) ) : ?>
								<img src="<?php echo esc_url( $book['cover_image'] ); ?>" alt="<?php echo esc_attr( $book['book_name'] ); ?>">
							<?php else : ?>
								<div class="mbw-no-cover"><?php echo esc_html__( 'No Image', 'my-book-widget' ); ?></div>
							<?php endif; ?>
						</div>
						
						<div class="mbw-book-details">
							<h3 class="mbw-book-title"><?php echo esc_html( $book['book_name'] ); ?></h3>
							<p class="mbw-book-author"><?php echo esc_html( $book['author_name'] ); ?></p>
							<span class="mbw-book-date"><?php echo esc_html( $book['release_date'] ); ?></span>
						</div>

					</div>
				<?php endforeach; ?>
			</div>

		</div>
		<?php
	}
}
