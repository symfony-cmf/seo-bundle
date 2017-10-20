<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Cmf\Bundle\SeoBundle\Cache\FileCache;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class FileCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowingExceptionForUnknownBaseDir()
    {
        $thrown = false;
        $baseDir = __DIR__.'/unknown';
        $dir = 'test';

        try {
            new FileCache($baseDir, $dir);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $thrown = true;
        }

        $this->assertTrue($thrown);
        $this->assertEquals($message, sprintf('The directory "%s" does not exist.', $baseDir));
    }

    public function testDirectoryCreation()
    {
        $baseDir = __DIR__.'/base';
        $dir = 'test';
        mkdir($baseDir);
        new FileCache($baseDir, $dir);

        $expectedDirectory = $baseDir.'/'.$dir;
        $this->assertTrue(is_dir($expectedDirectory));

        rmdir($expectedDirectory);
        rmdir($baseDir);
    }
}
