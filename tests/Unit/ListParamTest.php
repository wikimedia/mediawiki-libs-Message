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

namespace Wikimedia\Message\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Wikimedia\JsonCodec\JsonCodec;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\ListParam
 */
class ListParamTest extends TestCase {

	public static function provideConstruct(): array {
		return [
			'commaList' => [
				[
					ListType::COMMA,
					[
						1,
						2,
						3,
					],
				],
				'<list listType="comma"><text>1</text><text>2</text><text>3</text></list>',
			],
			'andList' => [
				[
					ListType::AND,
					[
						new ScalarParam( ParamType::NUM, 5 ),
						new MessageValue( 'key' ),
					],
				],
				'<list listType="text"><num>5</num><text><message key="key"></message></text></list>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args ) {
		[
			$type,
			$values,
		] = $args;
		$jsonCodec = new JsonCodec;
		$obj = new ListParam( $type, $values );

		$serialized = $jsonCodec->toJsonString( $obj );
		$newObj = $jsonCodec->newFromJsonString( $serialized );

		// XXX: would be nice to have a proper ::equals() method.
		$this->assertEquals( $obj->dump(), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		[
			$type,
			$values,
		] = $args;
		$mp = new ListParam( $type, $values );

		$expectValues = [];
		foreach ( $values as $v ) {
			$expectValues[] = $v instanceof MessageParam ? $v : new ScalarParam( ParamType::TEXT, $v );
		}

		$this->assertSame( ParamType::LIST, $mp->getType() );
		$this->assertSame( $type, $mp->getListType() );
		$this->assertEquals( $expectValues, $mp->getValue() );
		$this->assertSame( $expected, $mp->dump() );
	}

	public function testConstruct_badTypeConst() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$listType must be one of the ListType constants' );
		new ListParam( 'invalid', [] );
	}
}
