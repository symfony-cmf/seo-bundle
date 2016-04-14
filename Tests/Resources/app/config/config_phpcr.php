<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container->setParameter('cmf_testing.bundle_fqn', 'Symfony\Cmf\Bundle\SeoBundle');
$loader->import(CMF_TEST_CONFIG_DIR.'/phpcr_odm.php');
$loader->import(CMF_TEST_CONFIG_DIR.'/sonata_admin.php');
$loader->import(__DIR__.'/cmf_seo.phpcr.yml');
