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
 * Converts MessageSpecifier objects to localized plain text in a certain language.
 *
 * The caller cannot modify the details of message translation, such as which
 * of multiple sources the message is taken from. Any such flags may be injected
 * into the factory constructor.
 *
 * Implementations of TextFormatter are not required to perfectly format
 * any message in any language. Implementations should make a best effort to
 * produce human-readable text.
 */
interface ITextFormatter {
	/**
	 * Get the internal language code in which format() is
	 * @return string
	 */
	public function getLangCode(): string;

	/**
	 * Convert a MessageSpecifier to text.
	 *
	 * The result is not safe for use as raw HTML.
	 *
	 * @param MessageSpecifier $message
	 * @return string
	 * @return-taint tainted
	 */
	public function format( MessageSpecifier $message ): string;
}
