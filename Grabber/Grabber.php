<?php

/*
 * NzoGrabberExtension file.
 *
 * (c) Ala Eddine Khefifi <alakhefifi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nzo\GrabberBundle\Grabber;

use Goutte\Client;

/**
 * Class Grabber
 * @package Nzo\GrabberBundle\Grabber
 */
class Grabber
{

    private $url;
    private $domainUrl;
    private $notScannedUrlsTab;
    private $ScannedUrlsTab;
    private $extensionTab;
    private $client;
    private $exclude;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param $url
     * @param null $notScannedUrlsTab
     * @param null $extensionTab
     * @return array
     */
    public function grabUrls($url, $notScannedUrlsTab = null, $exclude = null, $extensionTab = null)
    {
        $this->cleanUpArray();

        $this->url = $url;
        $this->notScannedUrlsTab = $notScannedUrlsTab;
        $this->extensionTab = $extensionTab;
        $this->ScannedUrlsTab[] = $this->url;
        $this->domainUrl = $this->getDomaine($this->url);
        $this->exclude = $exclude;

        $i = 0;
        while (count($this->ScannedUrlsTab) > $i) {
            $this->crawler($this->ScannedUrlsTab[$i]);
            $i++;
        }

        return $this->ScannedUrlsTab;
    }

    /**
     * @param $newUrl
     * @return bool
     */
    private function crawler($newUrl)
    {
        try {
            $crawler = $this->client->request('GET', $newUrl);
        } catch (\Exception $e) {
            return false;
        }

        foreach ($crawler->filter('a[href]')->links() as $domElement) {
            $lien = $this->cleanUpUrl($domElement->getUri());
            if ($this->testExistanceScanned($lien)
                && $this->testExistanceNotScanned($lien)
                && $this->testDomaine($lien)
                && $this->testExtension($lien)
                && $this->notInExculde($lien)
            ) {
                $this->ScannedUrlsTab[] = $lien;
            }
        }

        return true;
    }

    /**
     * @param $lien
     * @return bool
     */
    private function testDomaine($lien)
    {
        return $this->getDomaine($lien) === $this->domainUrl;
    }

    private function testExistanceScanned($lien)
    {
        $lien = str_replace('://www.', '://', $lien);
        $stringUrl = substr($lien, 0, -1);
        $verifChar = substr($lien, -1) === '/';

        foreach ($this->ScannedUrlsTab as $val) {
            $val = str_replace('://www.', '://', $val);
            if ($lien === $val || ($verifChar && $stringUrl === $val) || (!$verifChar && $lien . '/' === $val)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $lien
     * @return bool
     */
    private function testExistanceNotScanned($lien)
    {
        $lien = str_replace('://www.', '://', $lien);
        if (empty($this->notScannedUrlsTab)) {
            return true;
        }
        $stringUrl = substr($lien, 0, -1);
        $verifChar = substr($lien, -1) === '/';
        foreach ($this->notScannedUrlsTab as $val) {
            $val = str_replace('://www.', '://', $val);
            if ($lien === $val || ($verifChar && $stringUrl === $val) || (!$verifChar && $lien . '/' === $val)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $lien
     * @return bool
     */
    private function testExtension($lien)
    {
        if (empty($this->extensionTab)) {
            return true;
        }

        if (substr($lien, -1) === '/' || substr($lien, -1) === '#') {
            $lien = substr($lien, 0, -1);
        }

        foreach ($this->extensionTab as $extension) {
            if (strtolower(substr($lien, -(strlen($extension) + 1))) === '.' . strtolower($extension)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $lien
     * @return string
     */
    private function cleanUpUrl($lien)
    {
        return (substr($lien, -1) === '#') ? substr($lien, 0, -1) : $lien;
    }

    /**
     * @param $url
     * @return array
     */
    public function grabImg($url)
    {
        $this->cleanUpArray();
        $crawler = $this->client->request('GET', $url);
        $this->url = $this->getDomaine($url);

        return $this->addHost($crawler->filter('img[src]')->extract(array('src')));
    }

    /**
     * @param $url
     * @return array
     */
    public function grabJs($url)
    {
        $this->cleanUpArray();
        $crawler = $this->client->request('GET', $url);
        $this->url = $this->getDomaine($url);

        return $this->addHost($crawler->filter('script[src]')->extract(array('src')));
    }

    /**
     * @param $url
     * @return array
     */
    public function grabCss($url)
    {
        $this->cleanUpArray();
        $crawler = $this->client->request('GET', $url);
        $this->url = $this->getDomaine($url);

        return $this->addHostCss($crawler->filter('link[href]')->extract(array('href')));
    }


    /**
     * @param $urlsTab
     * @return array
     */
    public function addHost($urlsTab)
    {
        foreach ($urlsTab as $val) {
            $sub = substr($val, 0, 7);
            if ('http://' === $sub || 'https:/' === $sub) {
                if ($this->getDomaine($val) === $this->url)
                    $this->ScannedUrlsTab[] = $val;
            } else {
                if ($val[0] === '/')
                    $this->ScannedUrlsTab[] = $this->url . $val;
                else
                    $this->ScannedUrlsTab[] = $this->url . '/' . $val;
            }
        }

        return $this->ScannedUrlsTab;
    }

    /**
     * @param $urlsTab
     * @return array
     */
    public function addHostCss($urlsTab)
    {
        foreach ($urlsTab as $val) {
            if (substr($val, -4) === '.css') {
                $sub = substr($val, 0, 7);
                if ('http://' === $sub || 'https:/' === $sub) {
                    if ($this->getDomaine($val) === $this->url)
                        $this->ScannedUrlsTab[] = $val;
                } else {
                    if ($val[0] === '/')
                        $this->ScannedUrlsTab[] = $this->url . $val;
                    else
                        $this->ScannedUrlsTab[] = $this->url . '/' . $val;
                }
            }
        }

        return $this->ScannedUrlsTab;
    }

    /**
     * @param $url
     * @return array
     */
    public function grabExtrat($url)
    {
        $this->cleanUpArray();
        $this->url = $this->cleanUpUrl($url);
        $crawler = $this->client->request('GET', $this->url);
        $this->url = $this->getDomaine($this->url);
        $this->addHostCss($crawler->filter('link[href]')->extract(array('href')));
        $this->addHost($crawler->filter('img[src]')->extract(array('src')));
        $this->addHost($crawler->filter('script[src]')->extract(array('src')));

        return $this->ScannedUrlsTab;
    }

    /**
     * @param $url
     * @return string
     */
    public function getDomaine($url)
    {
        $url = str_replace('://www.', '://', $url);
        return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
    }

    public function cleanUpArray()
    {
        $this->notScannedUrlsTab = array();
        $this->ScannedUrlsTab = array();
        $this->extensionTab = array();
    }

    public function notInExculde($lien)
    {
        if (empty($this->exclude)) {
            return true;
        }

        return strpos($lien, $this->exclude) === false;
    }
}