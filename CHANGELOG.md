Changelog
=========

1.2.0
-----

* **2016-04-04**: Moved content-language from http-equiv to a real header.
* **2016-03-31**: [Form] Form type for seo metadata set by_reference to false by default when ORM is active.
* **2015-09-30**: Add `cmf_seo_update_metadata` twig function for updating seo metadata from templates using
* **2015-09-08**: Add meta tag for language information
* **2015-08-20**: Added templates configuration and `exclusion_rules` (based on the request matcher) to
  the error handling configuration
* **2015-08-12**: Added configuration for the default data class of the `seo_metadata` form type.
* **2015-07-20**: Cleaned up the sitemap generation. If you used the unreleased 
  version of sitemaps, you will need to adjust your code. See https://github.com/symfony-cmf/SeoBundle/pull/225
  Options are available to keep all or no voters|guessers|loaders enabled or 
  enable them one by one by their service id.
* **2015-02-24**: Configuration for `content_key` moved to the `content_listener` 
  section, and its now possible to disable the content listener by setting 
  `cmf_seo.content_listener.enabled: false`
* **2015-02-14**: Added sitemap generation
* **2015-02-14**: [BC BREAK] Changed method visibility of 
  `SeoPresentation#getSeoMetadata()` from private to public.
* **2014-10-04**: Custom exception controller for error handling.

1.1.1
-----

Fixed CVE-2015-5723 in the FileCache

* **2015-09-01**: resolved a security vulnerability related to the FileCache.
                  Doctrine Common and ORM are also affected, so users are encouraged to
                  update all libraries and dependencies. The vulnerability has been assigned
                  [CVE-2015-5723](http://www.cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2015-5723)
                  and additional information on the issue may be found in
                  [this blog post](http://www.doctrine-project.org/2015/08/31/security_misconfiguration_vulnerability_in_various_doctrine_projects.html).

1.1.0-RC3
---------

* **2014-10-08**: Make execution of extractors sortable
* **2014-10-08**: Can't use admin extension without burgov/key-value-form-bundle now
* **2014-09-22**: Added the `SeoAwareTrait` to ease creation of SeoAware classes

1.1.0-RC1
---------

* **2014-08-02**: Implement alternate locale support and its configuration
* **2014-06-06**: Updated to PSR-4 autoloading

1.0.0
-----

* **2014-05-07**: Cleanup configuration for Sonata. `sonata_admin_extension` is now
  `sonata_admin_extension.enabled`.

1.0.0-RC2
---------

* **2014-04-17**: The `Seo` prefix is removed from a lot of classes (FQCN is
  shown here without the `Symfony\Cmf\Bundle\SeoBundle` part):

     * `DependencyInjection\SeoConfigValues` -> `DependencyInjection\ConfigValues`
     * `EventListener\SeoContentListener` -> `EventListener\ContentListener`
     * `Exception\SeoExtractorStrategyException` -> `Exception\ExtractorStrategyException`
     * `Extractor\SeoDescriptionExtractor` -> `Extractor\DescriptionExtractor`
     * `Extractor\SeoDescriptionReadInterface` -> `Extractor\DescriptionReadInterface`
     * `Extractor\SeoExtractorInterface` -> `Extractor\ExtractorInterface`
     * `Extractor\SeoKeywordsExtractor` -> `Extractor\KeywordsExtractor`
     * `Extractor\SeoKeywordsReadInterface` -> `Extractor\KeywordsReadInterface`
     * `Extractor\SeoOriginalRouteExtractor` -> `Extractor\OriginalRouteExtractor`
     * `Extractor\SeoOriginalRouteReadInterface` -> `Extractor\OriginalRouteReadInterface`
     * `Extractor\SeoOriginalUrlExtractor` -> `Extractor\OriginalUrlExtractor`
     * `Extractor\SeoOriginalUrlReadInterface` -> `Extractor\OriginalUrlReadInterface`
     * `Extractor\SeoTitleExtractor` -> `Extractor\TitleExtractor`
     * `Extractor\SeoTitleReadInterface` -> `Extractor\TitleReadInterface`

* **2014-04-17**: The `SeoAwareInterface`, `SeoPresentation` and
  `SeoPresentationInterface` class in the `Symfony\Cmf\Bundle\SeoBundle\Model`
  namespace are moved to the root namespace (`Symfony\Cmf\Bundle\SeoBundle`)

* **2014-04-17**: Add possibility for extra properties with extractors,
  persistence and admin support.

* **2014-04-11**: drop Symfony 2.2 compatibility

1.0.0-RC1
---------

* **2014-04-07**: The extractors now override the `SeoMetadata` retrieved from the `SeoAwareInterface`
* **2014-04-06**: The `SeoMetadata` changed the defaults to `''` instead of `null`

1.0.0-alpha1
------------

* **2014-03-31**: The options `document_class`, `admin_class` and `content_basepath`, `use_sonata_admin` of `cmf_seo.persistence.phpcr` are removed
* **2014-03-31**: The `metadata_listener` and `sonata_admin_extension` options are introduced in `cmf_seo`
* **2014-03-31**: Doctrine2 ORM support was added, it can be enabled by setting `cmf_seo.persistence.orm` to `true`
