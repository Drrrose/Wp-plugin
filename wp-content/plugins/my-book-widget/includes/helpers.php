<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fetch and parse books from a CSV or JSON file URL.
 *
 * @param string $url The URL of the file.
 * @return array|WP_Error Array of books or WP_Error on failure.
 */
function mbw_get_books_from_url( $url ) {
	if ( empty( $url ) ) {
		return new WP_Error( 'no_url', __( 'No file URL provided.', 'my-book-widget' ) );
	}

	// Get file extension
	$file_info = pathinfo( $url );
	$extension = isset( $file_info['extension'] ) ? strtolower( $file_info['extension'] ) : '';

	if ( ! in_array( $extension, [ 'csv', 'json' ] ) ) {
		return new WP_Error( 'invalid_type', __( 'Invalid file type. Only CSV and JSON are allowed.', 'my-book-widget' ) );
	}

	// Fetch remote file
	$response = wp_remote_get( $url );

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$body = wp_remote_retrieve_body( $response );

	if ( empty( $body ) ) {
		return new WP_Error( 'empty_file', __( 'The file is empty.', 'my-book-widget' ) );
	}

	if ( 'json' === $extension ) {
		return mbw_parse_json( $body );
	} elseif ( 'csv' === $extension ) {
		return mbw_parse_csv( $body );
	}

	return [];
}

/**
 * Parse JSON data.
 *
 * @param string $json_string
 * @return array|WP_Error
 */
function mbw_parse_json( $json_string ) {
	$data = json_decode( $json_string, true );

	if ( json_last_error() !== JSON_ERROR_NONE ) {
		return new WP_Error( 'json_error', __( 'Invalid JSON format.', 'my-book-widget' ) );
	}

	if ( ! is_array( $data ) ) {
		return new WP_Error( 'json_error', __( 'JSON data must be an array of objects.', 'my-book-widget' ) );
	}

	return mbw_normalize_books( $data );
}

/**
 * Parse CSV data.
 *
 * @param string $csv_string
 * @return array|WP_Error
 */
function mbw_parse_csv( $csv_string ) {
	$lines = explode( "\n", $csv_string );
	$data = [];
	$headers = [];

	foreach ( $lines as $index => $line ) {
		$line = trim( $line );
		if ( empty( $line ) ) {
			continue;
		}

		$row = str_getcsv( $line );

		if ( 0 === $index ) {
			// Handle BOM if present
			$row[0] = preg_replace( '/^\x{EF}\x{BB}\x{BF}/', '', $row[0] );
			$headers = $row;
			continue;
		}

		if ( count( $row ) === count( $headers ) ) {
			$data[] = array_combine( $headers, $row );
		}
	}

	return mbw_normalize_books( $data );
}

/**
 * Normalize book data to ensure consistent keys.
 *
 * @param array $data Raw data.
 * @return array Normalized data.
 */
function mbw_normalize_books( $data ) {
	$normalized = [];

	foreach ( $data as $item ) {
		// Basic validation: check if at least one required field exists to avoid empty rows
		if ( empty( $item['book_name'] ) && empty( $item['author_name'] ) ) {
			continue;
		}

		$normalized[] = [
			'book_name'    => isset( $item['book_name'] ) ? sanitize_text_field( $item['book_name'] ) : '',
			'author_name'  => isset( $item['author_name'] ) ? sanitize_text_field( $item['author_name'] ) : '',
			'release_date' => isset( $item['release_date'] ) ? sanitize_text_field( $item['release_date'] ) : '',
			'cover_image'  => isset( $item['cover_image'] ) ? esc_url( $item['cover_image'] ) : '',
		];
	}

	return $normalized;
}
