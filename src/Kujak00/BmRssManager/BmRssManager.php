<?php

namespace Kujak00\BmRssManager;

/**
 * RSS Manager.
 *
 * @author Paweł Kujaczyński <pawel@kujaczynski.pl>
 */
class BmRssManager
{
	/**
	 * RSS URL.
	 * @var string
	 */
	private $rss_url = 'http://php.net/feed.atom';
	
	
	
	
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param string $url
	 */
	public function setRssUrl($url)
	{
		$this->rss_url = $url;
	}
}
