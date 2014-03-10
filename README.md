NzoGrabberBundle
=====================

The **NzoGrabberBundle** is a Symfony2 Bundle used to Grabbe all types of links and URLs from any website.

Features include:

- Url Grabber for HTTP/HTTPS
- Url Grabber for HREF/SRC/IMG types
- Exclude any type of file by extension
- Prevent specified URLs from been Grabbed


Installation 
------------

### Through Composer:

Add the following lines in your `composer.json` file:

``` js
"require": {
    "nzo/grabber-bundle": "dev-master"
}
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

```php
     public function indexAction($url)
    {
        // get all URLs with no Exception
            $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url);

        // OR .. get all URLs but with Exception of $notScannedUrlsTab array.

            $notScannedUrlsTab = ['http://www.exemple.com/about']
            $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, $notScannedUrlsTab);

        // OR .. get get all URLs but with Exception of $notScannedUrlsTab array and file Extension

            $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, $notScannedUrlsTab, array('png', 'pdf'));

        // OR .. get get all URLs but with only Exception of file Extension

            $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, null, array('png', 'pdf'));

        //....

    }    
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

See [Resources/doc/LICENSE](https://github.com/NAYZO/NzoGrabberBundle/blob/master/Resources/doc/LICENSE)