<?php

declare(strict_types=1);

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;

class Scraper {
	
	/**
	 * @var string 
	 */
	private $url;

	/**
	 * @var string 
	 */
	private $filename;

	/**
	 * @var int 
	 */
	private $width;

	/**
	 * @var int 
	 */
	private $height;


	public function __construct(Config $config)
	{
		$this->filename =  $config->getString('screen_file');
		$this->width = $config->getInt('screen_width');
		$this->height = $config->getInt('screen_height');
		$this->url = $config->getString('url');
	}


	public function clean(): void 
	{
		if (file_exists($this->filename)) unlink($this->filename);
	}

    /**
     * Creates a webpage screenshot
     */
	public function scrap(): bool
	{
	    $this->clean();
	    
	    $browserFactory = new BrowserFactory();

	    // starts headless chrome
	    $browser = $browserFactory->createBrowser(['windowSize' => [$this->width, $this->height],]);

	    // creates a new page and navigate to an url
	    $page = $browser->createPage();
	    $page->navigate($this->url)->waitForNavigation(Page::NETWORK_IDLE);
	    
	    // screenshot
	    $page->screenshot(['clip' => $page->getFullPageClip()])->saveToFile($this->filename);
	    
	    // bye
	    $browser->close();

	    return file_exists($this->filename);
	}

}