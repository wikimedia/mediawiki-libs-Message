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

namespace Wikimedia\Message\Tests\Unit\Message;

use PHPUnit\Framework\TestCase;
use Wikimedia\JsonCodec\JsonCodec;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\DataMessageValue
 */
class DataMessageValueTest extends TestCase {

	public static function provideConstruct(): array {
		return [
			'empty' => [
				[ 'key' ],
				'<datamessage key="key" code="key"></datamessage>',
			],
			'withParam' => [
				[
					'key',
					[ 'a' ],
				],
				'<datamessage key="key" code="key"><params><text>a</text></params></datamessage>',
			],
			'withCode' => [
				[
					'key',
					[],
					'code',
				],
				'<datamessage key="key" code="code"></datamessage>',
			],
			'withData' => [
				[
					'key',
					[ new ScalarParam( ParamType::NUM, 1 ) ],
					'code',
					[ 'value' => 1 ],
				],
				'<datamessage key="key" code="code">' .
				'<params><num>1</num></params>' .
				'<data>{"value":1}</data>' .
				'</datamessage>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args ) {
		$jsonCodec = new JsonCodec;
		$obj = new DataMessageValue( ...$args );

		$serialized = $jsonCodec->toJsonString( $obj );
		$newObj = $jsonCodec->newFromJsonString( $serialized );

		// XXX: would be nice to have a proper ::equals() method.
		$this->assertEquals( $obj->dump(), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		$mv = new DataMessageValue( ...$args );
		$this->assertSame( $expected, $mv->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testNew( $args, $expected ) {
		$mv = DataMessageValue::new( ...$args );
		$this->assertSame( $expected, $mv->dump() );
	}

	public function testGetCode() {
		$mv = new DataMessageValue( 'key', [], 'code' );
		$this->assertSame( 'code', $mv->getCode() );
	}

	public function testGetData() {
		$mv = new DataMessageValue( 'key', [], 'code', [ 'data' => 'foobar' ] );
		$this->assertSame( [ 'data' => 'foobar' ], $mv->getData() );
	}
}
