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
	private $url = 'http://php.net/feed.atom';
	
	/**
	 * Data from RSS.
	 * @var object
	 */
	private $data;
	
	
	
	public function __construct()
	{
		$this->data = new \stdClass();
	}
	
	/**
	 * Set Rss url.
	 * @param string $url
	 * @return boolean
	 */
	public function setUrl($url)
	{
		if ($this->checkUrl($url))
		{
			$this->url = $url;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check url.
	 * @param string $url
	 * @return boolean
	 */
	private function checkUrl($url)
	{
		if (\filter_var ($url, FILTER_VALIDATE_URL))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Get data from rss url.
	 * @return boolean
	 */
	public function getData()
	{
		if ($xml_string = \file_get_contents($this->url))
		{
			if ($xml = \simplexml_load_string($xml_string))
			{
				$this->data_tmp = $xml;
				
				$this->data->title = (string)$xml->title;
				$this->data->author = (string)$xml->author->name;
				
				$this->data->entry = [];
				
				foreach ($xml->entry as $entry)
				{
					$entry_tmp = new \stdClass();
					$entry_tmp->title = (string)$entry->title;
					$entry_tmp->id = (string)$entry->id;
					
					$this->data->entry[] = $entry_tmp;
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Show data from Rss.
	 */
	public function showData()
	{
		if (\count((array)$this->data))
		{
			echo '<h1>'.$this->data->title.'<h1>';
		
			echo '<h3>Autor: '.$this->data->author.'</h3>';

			echo '<ul>';

			foreach ($this->data->entry as $entry)
			{
				echo '<li><a href="'.$entry->id.'" title="'.$entry->title.'">'.$entry->title.'</a></li>';
			}

			echo '</ul>';
		}
		else
		{
			echo '<h3>Brak danych</h3>';
		}
	}
	
	/**
	 * Print raw data for debug.
	 * @param mixed $data
	 */
	public function prePrintData($data)
	{
		echo '<pre>';
		
		\print_r($data);
		
		echo '</pre>';
	}
}
