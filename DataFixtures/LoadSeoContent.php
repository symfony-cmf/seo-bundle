<?php
/**
 * User: maximilian
 * Date: 2/10/14
 * Time: 11:28 PM
 * 
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DataFixtures;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadSeoContent extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager|\Doctrine\ODM\PHPCR\DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $seoDocument = new SeoAwareContent();
        $seoDocument->setParent($manager->find(null, '/cms/routes'));
        $seoDocument->setTitle('Seo Title');
        $seoDocument->setName('seo-content');
        $seoDocument->setBody('lore ipsum ...');

        $seoMetadata = new SeoMetadata();
        $seoMetadata->setTitle('Seo Title');
        $seoMetadata->setMetaKeywords('Content, Seo');
        $seoMetadata->setMetaDescription(
            'This content is the first seo content and have got a description, some keywords and a title'
        );
        $seoMetadata->setOriginalUrl('/en/company');

        $seoDocument->setSeoMetadata($seoMetadata);

        $manager->persist($seoDocument);

        //create the route for the document
        $route = new Route();
        $route->addDefaults(array(
                '_controller' => 'cmf_seo.controller:indexAction'
            ));
        $route->setContent($seoDocument);
        $route->setParent($manager->find(null, '/cms/routes'));
        $route->setName('seo-content');

        $manager->persist($route);

        $menuNode = new Menu();
        $menuNode->setContent($seoDocument);
        $menuNode->setName('seo-content');
        $menuNode->setParent($manager->find(null, '/cms/menu'));
        $menuNode->setLabel('Seo Content');

        $manager->persist($menuNode);

        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 90;
    }
}
