<?php

namespace Wikimedia\Message\Tests\Unit\Message;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Wikimedia\JsonCodec\JsonCodec;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\ScalarParam
 */
class ScalarParamTest extends TestCase {

	public static function provideConstruct(): array {
		return [
			'num' => [
				[
					ParamType::NUM,
					1,
				],
				'<num>1</num>',
			],
			'plain' => [
				[
					ParamType::PLAINTEXT,
					'foo & bar',
				],
				'<plaintext>foo &amp; bar</plaintext>',
			],
			'text' => [
				[
					ParamType::TEXT,
					new MessageValue( 'key' ),
				],
				'<text><message key="key"></message></text>',
			],
			'T377912' => [
				[
					ParamType::PLAINTEXT,
					T377912TestCase::class,
				],
				'<plaintext>' . T377912TestCase::class . '</plaintext>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args ) {
		[
			$type,
			$value,
		] = $args;
		$jsonCodec = new JsonCodec;
		$obj = new ScalarParam( $type, $value );

		$serialized = $jsonCodec->toJsonString( $obj );
		$newObj = $jsonCodec->newFromJsonString( $serialized );

		// XXX: would be nice to have a proper ::equals() method.
		$this->assertEquals( $obj->dump(), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		[
			$type,
			$value,
		] = $args;
		$mp = new ScalarParam( $type, $value );
		$this->assertSame( $type, $mp->getType() );
		$this->assertSame( $value, $mp->getValue() );
		$this->assertSame( $expected, $mp->dump() );
	}

	public function testConstruct_badType() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
		);
		new ScalarParam( ParamType::LIST, [] );
	}

	public function testConstruct_badTypeConst() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$type must be one of the ParamType constants' );
		new ScalarParam( 'invalid', '' );
	}

	public function testConstruct_badValueClass() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'Scalar parameter must be a string, number, Stringable, or MessageSpecifier; got stdClass'
		);
		new ScalarParam( ParamType::TEXT, new stdClass );
	}

}
