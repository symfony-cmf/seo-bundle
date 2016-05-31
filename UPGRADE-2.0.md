UPGRADE FROM 1.x to 2.0
=======================

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
