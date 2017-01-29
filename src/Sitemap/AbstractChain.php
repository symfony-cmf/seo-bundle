<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

/**
 * Abstraction for the chain of voters and loaders.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
abstract class AbstractChain
{
    /**
     * The list of entries by sitemap name and priority.
     *
     * @var array
     */
    private $items = [];

    /**
     * The list of default entries by priority.
     *
     * @var array
     */
    private $defaultItems = [];

    /**
     * Add an entry to the chain.
     *
     * An entry can be added to a specific sitemap or to all of them. The
     * higher the priority, the earlier the entry is in the chain. Sitemap
     * specific entries come before general entries of the same priority.
     *
     * @param object $item     the entry
     * @param int    $priority the higher the value, the earlier the item comes in the chain
     * @param string $sitemap  Name of the sitemap to add the entry for. Null adds the entry to all sitemaps.
     */
    public function addItem($item, $priority = 0, $sitemap = null)
    {
        if ($sitemap) {
            if (!isset($this->items[$sitemap])) {
                $this->items[$sitemap] = [];
            }
            $entries = &$this->items[$sitemap];
        } else {
            $entries = &$this->defaultItems;
        }

        if (!isset($entries[$priority])) {
            $entries[$priority] = [];
        }

        $entries[$priority][] = $item;
    }

    /**
     * Get the sorted list of chain entries for the specified sitemap.
     *
     * @param string $sitemap Name of the sitemap
     *
     * @return object[] priority sorted list of chain entries
     */
    protected function getSortedEntriesForSitemap($sitemap)
    {
        $priorities = array_keys($this->defaultItems);
        if (isset($this->items[$sitemap])) {
            $priorities = array_unique(array_merge($priorities, array_keys($this->items[$sitemap])));
        }

        rsort($priorities);

        $sortedItems = [];
        foreach ($priorities as $priority) {
            if (isset($this->items[$sitemap][$priority])) {
                // never happens if the sitemap has no specific entries at all
                $sortedItems = array_merge($sortedItems, $this->items[$sitemap][$priority]);
            }
            if (isset($this->defaultItems[$priority])) {
                $sortedItems = array_merge($sortedItems, $this->defaultItems[$priority]);
            }
        }

        return $sortedItems;
    }
}
