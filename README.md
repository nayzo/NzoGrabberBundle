NzoGrabberBundle
=====================

[![Build Status](https://travis-ci.org/NAYZO/NzoGrabberBundle.svg?branch=master)](https://travis-ci.org/NAYZO/NzoGrabberBundle)


The **NzoGrabberBundle** is a Symfony2 Bundle used to ``Crowl`` and to ``Grabbe`` all types of ``links``, ``URLs`` and ``Tags`` for (img, js, css) from any website.

Features include:

- Url Grabber for ``HTTP/HTTPS``
- Url Grabber for ``HREF / SRC / IMG`` types
- Exclude any type of file by extension
- Prevent specified URLs from Grabbing


Installation 
------------

### Through Composer:

Add the following lines in your `composer.json` file:

``` js
"require": {
    "nzo/grabber-bundle": "dev-master"
}
```
Install the bundle:

```
$ composer update
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
        $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url);

        //....
    }
```

OR .. get all URLs that does not figure in the ``exclude array``:

```php
     public function indexAction($url)
    {
        $notScannedUrlsTab = ['http://www.exemple.com/about']
        $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, $notScannedUrlsTab);

        //....
    }
```

OR .. you can exclude URLs that contains a specified ``text`` and also you can select by ``file extension``:

```php
     public function indexAction($url)
    {
        $exclude = 'someText_to_exclude';
        $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, null, $exclude, array('png', 'pdf'));

        //....
    }
```

OR .. get all URLs selected by ``file extension``:

```php
     public function indexAction($url)
    {
        $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, null, null, array('png', 'pdf'));

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

See [Resources/doc/LICENSE](https://github.com/NAYZO/NzoGrabberBundle/blob/master/Resources/doc/LICENSE)