<?php

declare(strict_types=1);

class Checker {

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $previous;

    /**
     * @var array
     */
    private $new_data = [];

    /**
     * @var array
     */
    private $changes = [];

    /**
     * @var string
     */
    private $filename;

    public function __construct(Config $config) {
        $this->config = $config;
        $this->filename = $this->config->getString('data_file');
        $this->previous = $this->loadData();
    }

    public function process(): bool 
    {
        $grabber = new Grabber($this->config);

        //grab html content of page and get changes     
        if ($grabber->grab() && !empty($this->changes = $this->getChanges($this->find($grabber->getBody()))))
        {
            //scrap screenshot
            $scrapper = new Scraper($this->config);
            if ($scrapper->scrap()) 
            {
                //send notifications
                $notifier = new Notifier($this->config, $this->changes);
                if ($notifier->sendSMSMail() && $notifier->sendMail())
                {
                    //if all passed, store data
                    $this->saveData();
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Prepares array of all changes. If empty, no changes were found
     */
    private function getChanges(array $new_data): array
    {
        $result = [];
        $max = max(count($this->previous), count($this->new_data = $new_data));
        for ($i = 0; $i < $max; $i++)
        {
            $old = $i < count($this->previous) ? $this->previous[$i] : '';
            $new = $i < count($this->new_data) ? $this->new_data[$i] : '';

            if ($old !== $new) $result[] = ['from' => $old, 'to' => $new];
        }
        return $result;
    }

    /**
     * Looks for pattern matches
     */
    private function find(string $where): array
    {
        $count = preg_match_all($this->config->getString('pattern'), $where, $matches, PREG_PATTERN_ORDER);
        return $count !== false ? end($matches) : []; 
    }

    /**
     * Reads previous changes
     */
    private function loadData(): array
    {
        return file_exists($this->filename) ? json_decode(file_get_contents($this->filename), true) : [];
    }

    /**
     * Stores new changes
     */
    private function saveData(): bool
    {
        return file_put_contents($this->filename, json_encode($this->new_data)) === false ?: true;
    }

}