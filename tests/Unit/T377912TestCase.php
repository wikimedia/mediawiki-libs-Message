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

declare( strict_types = 1 );

namespace Wikimedia\Message\Tests\Unit;

use LogicException;

/**
 * This class is part of a test for T377912,
 * where ScalarParam inappropriately tried to load a message param as a class.
 *
 * The class itself is irrelevant,
 * but any attempt to load it will trigger the LogicException below.
 * Mentioning the class as T377912TestCase::class is fine (does not trigger autoloading);
 * the test passes if ScalarParam does not try to load the param as a class
 * (e.g. by passing it into is_callable()).
 *
 * The file / class is called *TestCase so that it is allowed in the PHPUnit directory
 * without PHPUnit trying to load it automatically (as it would for T377912Test).
 *
 * @license GPL-2.0-or-later
 */
class T377912TestCase {

}

throw new LogicException( 'This file should never be loaded!' );
