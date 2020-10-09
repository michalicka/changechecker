<?php

declare(strict_types=1);

use Nette\Neon\Neon;

class Config {

	/**
	 * @var array
	 */
	protected $config = [
        'config_file' => __DIR__ . '/../config/config.neon',
        'data_file' => __DIR__ . '/../output/previous.json',
        'screen_file' => __DIR__ . '/../output/screenshot.jpg',
        'screen_width' => 1920,
        'screen_height' => 1080,
	];

    /**
     * Merge user config with defaults
     */
	public function __construct(array $argv)
	{
		$filename = count($argv) > 1 && file_exists($argv[1]) ? $argv[1] : $this->getString('config_file');
		
		$data = file_exists($filename) ? Neon::decode(file_get_contents($filename)) : [];
		$this->config = array_merge($this->config, $data);
		
		$chrome_path = $this->getString('chrome_path');
		if ($chrome_path) putenv('CHROME_PATH='.$chrome_path);
	}

	public function getString(string $var, string $default = ''): string
	{
		return $this->config[$var] ?? $default;
	}

	public function getInt(string $var, int $default = 0): int
	{
		return $this->config[$var] ?? $default;
	}

}
