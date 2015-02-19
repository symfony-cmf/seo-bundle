<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
abstract class SitemapItemChain
{
    /**
     * The list of guessers with sitemap name and priorities as key.
     *
     * @var
     */
    private $items;

    /**
     * @param object $item
     * @param int    $priority
     * @param string $sitemap
     */
    public function addItem($item, $priority = 0, $sitemap = 'default')
    {
        if (!isset($this->items[$sitemap])) {
            $this->items[$sitemap] = array();
        }

        if (!isset($this->items[$sitemap][$priority])) {
            $this->items[$sitemap][$priority] = array();
        }

        $this->items[$sitemap][$priority][] = $item;
    }

    /**
     * @param $sitemap
     *
     * @return array
     */
    protected function getSortedItemsBySitemap($sitemap)
    {
        if (!isset($this->items[$sitemap])) {
            return array();
        }

        ksort($this->items[$sitemap]);
        $sortedItems = array();
        foreach ($this->items[$sitemap] as $priority => $items) {
            foreach ($items as $item) {
                $sortedItems[] = $item;
            }
        }

        return $sortedItems;
    }
}
