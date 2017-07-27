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
	
	/**
	 * Data file name with saved data.
	 * @var string
	 */
	private $data_file_name = 'bmrssmanager.json';
	
	/**
	 * Data file dir.
	 * @var string
	 */
	private $data_file_dir = __DIR__;
	
	
	
	public function __construct()
	{
		$this->data = new \stdClass();
		$this->loadData();
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
					$entry_tmp->link = (string)$entry->id;
					
					$this->data->entry[] = $entry_tmp;
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Load data from file.
	 * @return boolean
	 */
	public function loadData()
	{
		$file = $this->data_file_dir.'/'.$this->data_file_name;
		
		if (\file_exists($file))
		{
			if ($handle = \fopen($file, 'r'))
			{
				$data = \fread($handle, \filesize($file));
			
				if ($data)
				{
					$this->data = \json_decode($data);
				
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Save data to file.
	 * @return boolean
	 */
	public function saveData()
	{
		$file = $this->data_file_dir.'/'.$this->data_file_name;
		
		if ($handle = \fopen($file, 'w'))
		{
			if (\fwrite($handle, \json_encode($this->data)))
			{
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
				echo '<li><a href="'.$entry->link.'" title="'.$entry->title.'">'.$entry->title.'</a></li>';
			}

			echo '</ul>';
		}
		else
		{
			echo '<h3>Brak danych</h3>';
		}
	}
	
	/**
	 * Get single entry from data by Id.
	 * @param type $id
	 * @return object
	 */
	public function getEntry($id)
	{
		return $this->data->entry[$id];
	}
	
	/**
	 * Add or set entry to data.
	 * @param string $title
	 * @param string $link
	 * @param bool|int $id
	 */
	public function addOrSetEntry($title, $link, $id = false)
	{
		$data = new \stdClass();
		$data->title = $title;
		$data->link = $link;
		
		if (\is_int($id))
		{
			$this->data->entry[$id] = $data;
		}
		else
		{
			$this->data->entry[] = $data;
		}
	}
	
	/**
	 * Delete entry from data by Id.
	 * @param int $id
	 */
	public function delEntry($id)
	{
		\array_splice($this->data->entry, $id, 1);
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
