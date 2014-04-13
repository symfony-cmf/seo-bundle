<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Cache;

/**
 * Caches extractors in the file system, using one file per content object.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class FileCache implements CacheInterface
{
    private $dir;

    public function __construct($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $dir));
        }
        if (!is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is not writable.', $dir));
        }

        $this->dir = rtrim($dir, '\\/');
    }

    /**
     * {@inheritDoc}
     */
    public function loadExtractorsFromCache($class)
    {
        $path = $this->dir.'/'.strtr($class, '\\', '-').'.cache.php';
        if (!file_exists($path)) {
            return null;
        }

        return include $path;
    }

    /**
     * {@inheritDoc}
     */
    public function putExtractorsInCache($class, array $extractors)
    {
        $path = $this->dir.'/'.strtr($class, '\\', '-').'.cache.php';

        $reflection = new \ReflectionClass($class);
        $extractors = new ExtractorCollection($extractors, $reflection->getFileName());

        $tmpFile = tempnam($this->dir, 'metadata-cache');
        file_put_contents($tmpFile, '<?php return unserialize('.var_export(serialize($extractors), true).');');
        chmod($tmpFile, 0666 & ~umask());

        $this->renameFile($tmpFile, $path);
    }

    /**
     * Renames a file with fallback for windows.
     *
     * @param string $source
     * @param string $target
     *
     * @throws \RuntimeException When the renaming can't be completed succesfully
     */
    private function renameFile($source, $target) {
        if (false === @rename($source, $target)) {
            if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                if (false === copy($source, $target)) {
                    throw new \RuntimeException(sprintf('(WIN) Could not write new cache file to %s.', $target));
                }
                if (false === unlink($source)) {
                    throw new \RuntimeException(sprintf('(WIN) Could not delete temp cache file to %s.', $source));
                }
            } else {
                throw new \RuntimeException(sprintf('Could not write new cache file to %s.', $target));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isOptional()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function warmUp($cacheDir)
    {
        mkdir($this->dir, 0777, true);
    }
}
