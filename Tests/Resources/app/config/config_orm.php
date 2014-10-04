<?php

$container->setParameter('cmf_testing.bundle_fqn', 'Symfony\Cmf\Bundle\SeoBundle');
$loader->import(CMF_TEST_CONFIG_DIR.'/doctrine_orm.php');
$loader->import(__DIR__.'/cmf_seo.orm.yml');

$container->loadFromExtension('doctrine', array(
    'orm' => array(
        'mappings' => array(
            'tests_fixtures' => array(
                'type' => 'annotation',
                'prefix' => 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Entity',
                'dir' => $container->getParameter('kernel.root_dir').'/../Entity',
                'is_bundle' => false,
            ),
        ),
    ),
));
