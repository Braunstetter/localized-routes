# LocalizedRoutesBundle

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Braunstetter/localized-routes/?branch=main)
[![Build Status](https://app.travis-ci.com/Braunstetter/localized-routes.svg?branch=main)](https://app.travis-ci.com/Braunstetter/localized-routes)
[![Total Downloads](http://poser.pugx.org/braunstetter/localized-routes/downloads)](https://packagist.org/packages/braunstetter/localized-routes)
[![License](http://poser.pugx.org/braunstetter/localized-routes/license)](https://packagist.org/packages/braunstetter/localized-routes)

This bundle simply redirects your requests to a locale prefixed route. 

So `/news` is going to be `/en/news`, when the current language is `en`.

Localized routes are something that has been repeatedly discussed on sites like StackOverflow in the past.

I think it's better to use a reusable bundle for this purpose instead of writing a new listener for each project.
This bundle can then be further developed and improved by the community.

# Installation

`composer require braunstetter/localized-routes`

You need to set these two parameters:

```yaml
parameters:
  app_locales: de|en
  locale: en
```

Afterwards you can prefix your controller routes: 

```yaml
# anntoations.yaml
controllers:
    resource: ../../src/Controller/
    type: annotation
    prefix: /{_locale}
    requirements:
        _locale: '%app_locales%'
    defaults:
        _locales: '%locale%'
```

```yaml
# This is necessary to redirect from /
# Symfony by default will not find a route for / (because it is /_locale/ now) 
# If you forget this - Symfony will show you the welcome screen before the LocaleRewriteSubscriber can do it's work. 
home_fallback:
    path: /
    controller: Symfony\Bundle\FrameworkBundle\Controller\RedirectController::urlRedirectAction
    defaults:
        permanent: true
```


You're done! Your blanc routes are getting redirected to localized ones.

If a route contains an unsupported `_locale` string it is getting redirected to the default locale.

> This bundle can certainly be improved.
> If you have any questions and/or suggestions for improvements. Don't be afraid to create a new issue or submit a PR.
