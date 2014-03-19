<?php
$container->loadFromExtension('cmf_seo', array(
    'title' => array(
        'default'   => 'Default title',
        'pattern'   => 'append',
        'separator' => ' | ',
    ),
    'content' => array('pattern' => 'redirect'),
    'persistence' => array(
        'phpcr' => array(
            'use_sonata_admin'  => true,
        ),
    ),
));
