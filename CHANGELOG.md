Changelog
=========

1.0.0-RC2
---------

* **2014-04-14**: Add possibility for arbitrary properties with extractors, persistence and admin support
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
