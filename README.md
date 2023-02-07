# LocalizedRoutesBundle

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/?branch=main)
[![Build Status](https://app.travis-ci.com/Braunstetter/localized-routes.svg?branch=main)](https://app.travis-ci.com/Braunstetter/localized-routes)
[![Total Downloads](http://poser.pugx.org/braunstetter/localized-routes/downloads)](https://packagist.org/packages/braunstetter/localized-routes)
[![License](http://poser.pugx.org/braunstetter/localized-routes/license)](https://packagist.org/packages/braunstetter/localized-routes)

This bundle simply redirects your requests to a locale prefixed route.

So `/news` is going to be `/en/news`, if the current language is `en`.

Localized routes are something that has been repeatedly discussed on sites like StackOverflow in the past.

I think it's better to use a well tested bundle for this purpose instead of writing a new listener for each project.
This bundle can then be further developed and improved by the community.

# Installation

`composer require braunstetter/localized-routes`

Now you can prefix your controller routes:

```yaml
# annotations.yaml
controllers:
  resource: ../../src/Controller/
  type: annotation
  prefix: /{_locale}
```

You're done! Your blank routes are getting redirected to localized ones.

# Configuration

Since the framework bundle already provides two configuration values (default_locale, enabled_locales), we can use this
to configure our forwarding process.

```yaml
framework:
  default_locale: en
  enabled_locales: [ 'es', 'en' ]
```

## default_locale
This is just the fallback locale. If not set, Symfony will try to determine a value based on your system settings. Most likely 'en'.

## enabled_locales

If this value is not specified, then all locales are allowed.
If you transfer an array of values here, the selection is restricted accordingly.

# Parameters

This bundle exposes the `enabled_locales` and `default_locale` configuration values of the framework-bundle as parameters.
In addition to this, you also have an `enabled_locales_string` parameter - joining your enabled_locales e.g.: `de|en|es`

So you can use these locale-specific parameters in your configuration files: 

```yaml
# annotations.yaml
controllers:
  resource: ../../src/Controller/
  type: annotation
  prefix: /{_locale}
  requirements:
    _locale: '%enabled_locales_string%'
  defaults:
    _locales: '%default_locale%'
```

> This bundle can certainly be improved.
> If you have any questions and/or suggestions for improvements, don't hesitate to create a new issue or submit a PR.

# Todo
- add attributes to documentation
- add explanation for a language switcher to documentation
- redirect on not supported locale to default locale - like `/es/my_route` when just `de|en` are supported