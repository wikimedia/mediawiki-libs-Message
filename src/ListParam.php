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
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 *
 * @newable
 */
class ListParam extends MessageParam {
	use JsonCodecableTrait;

	private string $listType;

	/**
	 * @stable to call.
	 *
	 * @param string $listType One of the ListType constants.
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] $elements Values in the list.
	 *  Values that are not instances of MessageParam are wrapped using ParamType::TEXT.
	 */
	public function __construct( string $listType, array $elements ) {
		if ( !in_array( $listType, ListType::cases() ) ) {
			throw new InvalidArgumentException( '$listType must be one of the ListType constants' );
		}
		$this->type = ParamType::LIST;
		$this->listType = $listType;
		$this->value = [];
		foreach ( $elements as $element ) {
			if ( $element instanceof MessageParam ) {
				$this->value[] = $element;
			} else {
				$this->value[] = new ScalarParam( ParamType::TEXT, $element );
			}
		}
	}

	/**
	 * Get the type of the list
	 *
	 * @return string One of the ListType constants
	 */
	public function getListType(): string {
		return $this->listType;
	}

	public function dump(): string {
		$contents = '';
		foreach ( $this->value as $element ) {
			$contents .= $element->dump();
		}
		return "<$this->type listType=\"$this->listType\">$contents</$this->type>";
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return [
			$this->type => $this->value,
			'type' => $this->listType,
		];
	}

	public static function newFromJsonArray( array $json ): ListParam {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		if ( count( $json ) !== 2 || !isset( $json[ParamType::LIST] ) || !isset( $json['type'] ) ) {
			throw new InvalidArgumentException( 'Invalid format' );
		}
		return new self( $json['type'], $json[ParamType::LIST] );
	}
}
