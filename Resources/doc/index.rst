NzoGrabberBundle
================

[![Build Status](https://travis-ci.org/nayzo/NzoGrabberBundle.svg?branch=master)](https://travis-ci.org/nayzo/NzoGrabberBundle)
[![Latest Stable Version](https://poser.pugx.org/nzo/grabber-bundle/v/stable)](https://packagist.org/packages/nzo/grabber-bundle)


The **NzoGrabberBundle** is a Symfony Bundle used to ``Crawl`` and to ``Grab`` all types of ``links``, ``URLs`` and ``Tags`` for (img, js, css) from any website.

Features include:

- Compatible Symfony version 3 & 4
- Url Grabber/Crawler for ``HTTP/HTTPS``
- Url Grabber/Crawler for ``HREF / SRC / IMG`` types
- Exclude any type of file by extension
- Prevent specified URLs from Grabbing
- Compatible php version 5 & 7


Installation 
------------

### Through Composer:

Install the bundle:

```
$ composer require nzo/grabber-bundle
```



### Register the bundle in app/AppKernel.php:

``` php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        // ...
        new Nzo\GrabberBundle\NzoGrabberBundle(),
    );
}
```

Usage
-----

In the controller use the Grabber service and specify the options needed:

Get all URLs:

```php
     public function indexAction($url)
    {
        $tableOfUrls = $this->get('nzo_grabber.grabber')->grabUrls($url);

        //....
    }
```

OR .. get all URLs not recursively:

Get all URLs no recursive:

```php
     public function indexAction($url)
    {
        $tableOfUrls = $this->get('nzo_grabber.grabber')->grabUrlsNoRecursive($url);

        //....
    }
```

OR .. get all URLs that does not figure in the ``exclude array``:

```php
     public function indexAction($url)
    {
        $notScannedUrlsTab = ['http://www.exemple.com/about']
        $tableOfUrls = $this->get('nzo_grabber.grabber')->grabUrls($url, $notScannedUrlsTab);

        //....
    }
```

OR .. you can exclude URLs that contains a specified ``text`` and also you can select by ``file extension``:

```php
     public function indexAction($url)
    {
        $exclude = 'someText_to_exclude';
        $tableOfUrls = $this->get('nzo_grabber.grabber')->grabUrls($url, null, $exclude, array('png', 'pdf'));

        //....
    }
```

OR .. get all URLs selected by ``file extension``:

```php
     public function indexAction($url)
    {
        $tableOfUrls = $this->get('nzo_grabber.grabber')->grabUrls($url, null, null, array('png', 'pdf'));

        //....
    }
```

OR .. get all ``Img Files`` from the specified URL:

```php
     public function indexAction($url)
    {
        $img = $this->get('nzo_grabber.grabber')->grabImg($url);

        //....
    }
```

OR .. get all ``Js Files`` from the specified URL:

```php
     public function indexAction($url)
    {
        $js = $this->get('nzo_grabber.grabber')->grabJs($url);

        //....
    }
```

OR .. get all ``Css Files`` from the specified URL:

```php
     public function indexAction($url)
    {
        $css = $this->get('nzo_grabber.grabber')->grabCss($url);

        //....
    }
```

OR .. get all ``Css``, ``Img`` and ``Js`` Files from the specified URL:

```php
     public function indexAction($url)
    {
        $extrat = $this->get('nzo_grabber.grabber')->grabExtrat($url);

        //....
    }
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

See [Resources/doc/LICENSE](https://github.com/nayzo/NzoGrabberBundle/blob/master/Resources/doc/LICENSE)
