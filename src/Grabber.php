<?php

declare(strict_types=1);

class Grabber {
	

	/**
	 * @var string 
	 */
	private $url;


	/**
	 * @var int 
	 */
	private $status;
	

	/**
	 * @var string 
	 */
	private $html;
	

	/**
	 * @var string 
	 */
	private $body;
	

	public function __construct(Config $config)
	{
		$this->url = $config->getString('url');
	}


    /**
     * Grabs HTML content of page
     */
	public function grab(): bool
	{
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $this->url);
		$this->status = $response->getStatusCode();
		$this->body = $response->getBody()->getContents();
		return $this->isLoaded();
	}


	public function isLoaded(): bool
	{
		return $this->status === 200;
	}

    public function getBody(): string
    {
        return $this->body;
    }
}