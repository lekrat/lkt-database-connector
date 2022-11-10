<?php

namespace Lkt\DatabaseConnectors\Cache;

use Cocur\Slugify\Slugify;

class QueryResultsCache
{
    protected $connector = '';
    protected $calls = [];
    protected $query = '';
    protected $latestResults = [];

    public function __construct(string $connector, string $query, $latestResults)
    {
        $this->connector = $connector;
        $this->query = $query;
        $this->latestResults = $latestResults;
        $this->calls[] = new \DateTime();
    }

    /**
     * @param string $connector
     * @param string $query
     * @param $latestResults
     * @return $this
     */
    public static function create(string $connector, string $query, $latestResults): self
    {
        return new static($connector, $query, $latestResults);
    }

    /**
     * @param $latestResults
     * @return $this
     */
    public function update($latestResults): self
    {
        $this->latestResults = $latestResults;
        $this->calls[] = new \DateTime();
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheIndex(): string
    {
        return static::buildCacheIndex($this->connector, $this->query);
    }

    /**
     * @param string $connector
     * @param string $query
     * @return string
     */
    public static function buildCacheIndex(string $connector, string $query): string
    {
        $slug = new Slugify();
        $query = $slug->slugify($query);
        return "{$connector}:->{$query}";
    }

    /**
     * @return array
     */
    public function getLatestResults(): array
    {
        return $this->latestResults;
    }
}