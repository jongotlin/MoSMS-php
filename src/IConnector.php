<?php
/*
 * This file is part of the MoSMS package.
 *
 * (c) Timmy Sjöstedt <git@iostream.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace brajox\MoSMS;

interface IConnector
{
	/**
	 * Handle an URI
	 *
	 * @return string response
	 */
	public function execute($uri);
}
