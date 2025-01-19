<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace Wikimedia\Message;

use InvalidArgumentException;
use Stringable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Value object representing a message parameter holding a single value.
 *
 * Message parameter classes are pure value objects and are safely newable.
 *
 * @newable
 */
class ScalarParam extends MessageParam {
	use JsonCodecableTrait;

	/**
	 * Construct a text parameter
	 *
	 * @stable to call.
	 *
	 * @param string $type One of the ParamType constants.
	 * @param string|int|float|MessageSpecifier|Stringable $value
	 */
	public function __construct( string $type, $value ) {
		if ( !in_array( $type, ParamType::cases() ) ) {
			throw new InvalidArgumentException( '$type must be one of the ParamType constants' );
		}
		if ( $type === ParamType::LIST ) {
			throw new InvalidArgumentException(
				'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
			);
		}
		if ( $value instanceof MessageSpecifier ) {
			// Ensure that $this->value is JSON-serializable, even if $value is not
			$value = MessageValue::newFromSpecifier( $value );
		} elseif ( is_object( $value ) && (
			$value instanceof Stringable || is_callable( [ $value, '__toString' ] )
		) ) {
			// TODO: Remove separate '__toString' check above once we drop PHP 7.4
			$value = (string)$value;
		} elseif ( !is_string( $value ) && !is_numeric( $value ) ) {
			$valType = get_debug_type( $value );
			if ( $value === null || is_bool( $value ) ) {
				trigger_error(
					"Using $valType as a message parameter was deprecated in version 1.0.0",
					E_USER_DEPRECATED
				);
				$value = (string)$value;
			} else {
				throw new InvalidArgumentException(
					"Scalar parameter must be a string, number, Stringable, or MessageSpecifier; got $valType"
				);
			}
		}

		$this->type = $type;
		$this->value = $value;
	}

	public function dump(): string {
		if ( $this->value instanceof MessageValue ) {
			$contents = $this->value->dump();
		} else {
			$contents = htmlspecialchars( (string)$this->value );
		}
		return "<$this->type>" . $contents . "</$this->type>";
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return [
			$this->type => $this->value,
		];
	}

	public static function newFromJsonArray( array $json ): ScalarParam {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		if ( count( $json ) !== 1 ) {
			throw new InvalidArgumentException( 'Invalid format' );
		}

		$type = key( $json );
		$value = current( $json );

		return new self( $type, $value );
	}
}
