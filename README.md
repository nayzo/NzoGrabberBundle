NzoGrabberBundle
=====================

The **NzoGrabberBundle** is a Symfony2 Bundle used to Grabbe all types of links, URLs and Tags for (img, js, css) from any website.

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

        // OR .. get all URLs but with Exception of $notScannedUrlsTab array and file Extension
        //    Also you can exclude any URL containing the `` $exclude `` value.

            $exclude = 'someText';
            $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, $notScannedUrlsTab, $exclude, array('png', 'pdf'));

        // OR .. get all URLs but with only Exception of file Extension

            $TableOfUrls = $this->get('nzo_grabber.grabber')->graburls($url, null, null, array('png', 'pdf'));

         // OR .. get all Img Files from the specified URL

            $img = $this->get('nzo_grabber.grabber')->grabImg($url);

        // OR .. get all Js Files from the specified URL

            $js = $this->get('nzo_grabber.grabber')->grabJs($url);

        // OR .. get all Css Files from the specified URL

            $css = $this->get('nzo_grabber.grabber')->grabCss($url);

        // OR .. get all Css and Img and Js Files from the specified URL

            $extrat = $this->get('nzo_grabber.grabber')->grabExtrat($url);

        //....

    }    
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

See [Resources/doc/LICENSE](https://github.com/NAYZO/NzoGrabberBundle/blob/master/Resources/doc/LICENSE)