<?php
namespace MoSMS;

interface IConnector
{
	/**
	 * Handle an URI
	 *
	 * @return string response
	 */
	public function execute($uri);
}
