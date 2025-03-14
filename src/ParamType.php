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

/**
 * The constants used to specify parameter types. The values of the constants
 * are an unstable implementation detail.
 *
 * Unless otherwise noted, these should be used with an instance of ScalarParam.
 */
class ParamType {
	/** A simple text string or another MessageValue, not otherwise formatted. */
	public const TEXT = 'text';

	/** A number, to be formatted using local digits and separators */
	public const NUM = 'num';

	/**
	 * A number of seconds, to be formatted as natural language text.
	 * The value will be output exactly.
	 */
	public const DURATION_LONG = 'duration';

	/**
	 * A number of seconds, to be formatted as natural language text in an abbreviated way.
	 * The output will be rounded to an appropriate magnitude.
	 */
	public const DURATION_SHORT = 'period';

	/**
	 * An expiry time.
	 *
	 * The input is either a timestamp in one of the formats accepted by the
	 * Wikimedia\Timestamp library, or "infinity" if the thing doesn't expire.
	 *
	 * The output is a date and time in local format, or a string representing
	 * an "infinite" expiry.
	 */
	public const EXPIRY = 'expiry';

	/**
	 * A date time in one of the formats accepted by the Wikimedia\Timestamp library.
	 *
	 * The output is a date and time in local format.
	 */
	public const DATETIME = 'datetime';

	/**
	 * A date in one of the formats accepted by the Wikimedia\Timestamp library.
	 *
	 * The output is a date in local format.
	 */
	public const DATE = 'date';

	/**
	 * A time in one of the formats accepted by the Wikimedia\Timestamp library.
	 *
	 * The output is a time in local format.
	 */
	public const TIME = 'time';

	/**
	 * User Group
	 * @since MediaWiki 1.38
	 */
	public const GROUP = 'group';

	/** A number of bytes. The output will be rounded to an appropriate magnitude. */
	public const SIZE = 'size';

	/** A number of bits per second. The output will be rounded to an appropriate magnitude. */
	public const BITRATE = 'bitrate';

	/** A list of values. Must be used with ListParam. */
	public const LIST = 'list';

	/**
	 * A text parameter which is substituted after formatter processing.
	 *
	 * The creator of the parameter and message is responsible for ensuring
	 * that the value will be safe for the intended output format, and
	 * documenting what that intended output format is.
	 */
	public const RAW = 'raw';

	/**
	 * A text parameter which is substituted after formatter processing.
	 * The output will be escaped as appropriate for the output format so
	 * as to represent plain text rather than any sort of markup.
	 */
	public const PLAINTEXT = 'plaintext';

	public static function cases(): array {
		return [
			self::TEXT,
			self::NUM,
			self::DURATION_LONG,
			self::DURATION_SHORT,
			self::EXPIRY,
			self::DATETIME,
			self::DATE,
			self::TIME,
			self::GROUP,
			self::SIZE,
			self::BITRATE,
			self::LIST,
			self::RAW,
			self::PLAINTEXT,
		];
	}
}
