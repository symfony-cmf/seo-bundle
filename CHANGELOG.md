Changelog
=========

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
