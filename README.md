# Symfony CMF Seo Bundle

[![Build Status](https://secure.travis-ci.org/symfony-cmf/ContentBundle.png)](http://travis-ci.org/symfony-cmf/SeoBundle)

This bundle is part of the [Symfony Content Management Framework (CMF)](http://cmf.symfony.com/)
and licensed under the [MIT License](LICENSE).

This bundle enables contents to be shown in a SEO conform way. By the help of the SonataSeoBundle
this bundle renders SEO-Metadata.
For now it supports:
- Title
- Description
- Keywords
- duplicate content solutions

The SeoBundle adds a `SeoAwareInterface` with a `SeoMetadata` class as encapsulation for
all the seo stuff.

## Requirements

* Symfony 2.2.x
* See also the `require` section of [composer.json](composer.json)

## Documentation

###Configuration

started to add a configuration, which can live on top of the config for SonataSeoBundle.
A configuration like:

```
cmf_seo:
    title:
        default: My SEO title
        bond_by: ' | '
    description: My default description
    keys: default, key, other
    content:
      strategy: canonical
```

Would create a title like `content_specific | My SEO title` cause the default title strategy is `prepend`. Other
values are `append` and `replace`.
This will add the default description combined with the config specific one into a meta tag.
The same will be done for the keywords.
Duplicate content will be solved by a canonical link, redirect will be default (todo: later)

###Later

For the install guide and reference, see:

* [SeoBundle documentation](http://symfony.com/doc/master/cmf/bundles/seo/index.html)

See also:

* [All Symfony CMF documentation](http://symfony.com/doc/master/cmf/index.html) - complete Symfony CMF reference
* [Symfony CMF Website](http://cmf.symfony.com/) - introduction, live demo, support and community links


## Contributing

Pull requests are welcome. Please see our
[CONTRIBUTING](https://github.com/symfony-cmf/symfony-cmf/blob/master/CONTRIBUTING.md)
guide.

Unit and/or functional tests exist for this bundle. See the
[Testing documentation](http://symfony.com/doc/master/cmf/components/testing.html)
for a guide to running the tests.

Thanks to
[everyone who has contributed](https://github.com/symfony-cmf/ContentBundle/contributors) already.
