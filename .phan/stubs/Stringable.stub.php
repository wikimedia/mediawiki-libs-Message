<?php
// This stub is required for compatibility with PHP 7, as the Stringable interface was introduced in PHP 8.
// TODO: Remove this file when PHP 7 support is dropped.
// phpcs:ignoreFile
if ( !interface_exists( 'Stringable' ) ) {
	interface Stringable {
		public function __toString(): string;
	}
}
