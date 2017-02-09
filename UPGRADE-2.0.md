UPGRADE FROM 1.x to 2.0
=======================

### SonataAdmin Support

 * The Admin extension to edit seo metadata was moved into `symfony-cmf/sonata-admin-integration-bundle`.
   With the move, the admin extension service names also changed. If you are using the cmf_seo.admin_extension service,
   you need to adjust your configuration.
   
   Before:
   
   ```yaml
        # app/config/config.yml
     
        sonata_admin:
            extensions:
                cmf_seo.admin_extension:
                   implements:
                       - Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface
   ```

    After:
       
   ```yaml
        # app/config/config.yml
                
        sonata_admin:
            extensions:
                cmf_sonata_admin_integration.seo.admin_extension:
                    implements:
                       - Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface
   ```

### SuggestionProviderController

 * The `showAction` has been renamed to `listAction` and the fourth argument is removed.

   Before:

   ```yaml
   twig:
       exception_controller: cmf_seo.error.suggestion_provider.controller:showAction
   ```

   After:

   ```yaml
   twig:
       exception_controller: cmf_seo.error.suggestion_provider.controller:listAction
   ```

## Doctrine PHPCR ODM

 * It is no longer possible to add a child to the `SeoMetadata` document. This
   behaviour can be changed by overriding the `child-class` setting of the
   PHPCR ODM mapping.
