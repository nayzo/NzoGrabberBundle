<?php

namespace Nzo\GrabberBundle\Grabber;
use Goutte\Client;

/**
 * GrabberBundle.
 *
 * @author Ala Eddine Khefifi <alakhefifi@gmail.com>
 * Website   www.alakhefifi.com
 */
class Grabber {

    private $url;
    private $domainUrl;
    private $notScannedUrlsTab = array();
    private $ScannedUrlsTab = array();
    private $extensionTab = array();
    public function __construct(){

    }

    public function grabUrls($url, $notScannedUrlsTab=null, $extensionTab=null){
        $this->url = $url;
        $this->notScannedUrlsTab = $notScannedUrlsTab;
        $this->extensionTab      = $extensionTab;
        $this->ScannedUrlsTab[]  = $this->url;
        $this->domainUrl = $this->getDomaine($this->url);

        $i=0;
        while(count($this->ScannedUrlsTab) > $i){
            $this->crawler($this->ScannedUrlsTab[$i]);
            $i++;
        }
        return $this->ScannedUrlsTab;
    }

    private function crawler($newUrl){
        $client = new Client();
        try{
            $crawler = $client->request('GET', $newUrl);
        } catch (\Exception $e) {return;}

        foreach ($crawler->filter('a[href]')->links() as $domElement) {
            $lien = $this->cleanUpUrl($domElement->getUri());
            if( $this->testExistanceScanned($lien) && $this->testExistanceNotScanned($lien) && $this->testDomaine($lien) && $this->testExtension($lien) )
                array_push($this->ScannedUrlsTab, $lien);

        }
        return true;
    }

    private function testDomaine($lien){
        return $this->getDomaine($lien) === $this->domainUrl;
    }

    private function testExistanceScanned($lien){
        $verif = true;
        $stringUrl = substr($lien, 0, -1);
        $verifChar = substr($lien, -1) === '/';

        foreach($this->ScannedUrlsTab as $val){
            if($lien === $val || ($verifChar  && $stringUrl === $val) || (!$verifChar && $lien.'/' === $val) )
                $verif = false;
        }
        return $verif;
    }

    private function testExistanceNotScanned($lien){
        if(!$this->notScannedUrlsTab)
            return true;
        $verif = true;
        $stringUrl = substr($lien, 0, -1);
        $verifChar = substr($lien, -1) === '/';
        foreach($this->notScannedUrlsTab as $val){
            if($lien === $val || ($verifChar  && $stringUrl === $val) || (!$verifChar && $lien.'/' === $val) )
                $verif = false;
        }
        return $verif;
    }

    private function testExtension($lien){
        if(!$this->extensionTab)
            return true;
        if(substr($lien, -1) === '/' || substr($lien, -1) === '#')
            $lien = substr($lien, 0, -1);
        foreach($this->extensionTab as $extension){
            if( strtolower( substr($lien, -(strlen($extension)+1)) ) === '.' . strtolower($extension) )
                return false;
        }
        return true;
    }

    private function cleanUpUrl($lien){
        return ( substr($lien, -1) === '#') ? substr($lien, 0, -1) : $lien;
    }

    public function grabImg($url){
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $this->url = $this->getDomaine($url);
        return $this->addHost($crawler->filter('img[src]')->extract(array('src')));
    }

    public function grabJs($url){
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $this->url = $this->getDomaine($url);
        return $this->addHost($crawler->filter('script[src]')->extract(array('src')));
    }

    public function grabCss($url){
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $this->url = $this->getDomaine($url);
        return $this->addHostCss($crawler->filter('link[href]')->extract(array('href')));
    }


    public function addHost($urlsTab){

        foreach($urlsTab as $val){
            $sub = substr($val, 0, 7);
            if( 'http://' ===  $sub || 'https:/' ===  $sub ){
                if($this->getDomaine($val) === $this->url)
                    $this->ScannedUrlsTab[] = $val;
            }
            else{
                if($val[0] === '/')
                    $this->ScannedUrlsTab[] = $this->url .  $val;
                else
                    $this->ScannedUrlsTab[] = $this->url . '/' . $val;
            }
        }
        return $this->ScannedUrlsTab;
    }

    public function addHostCss($urlsTab){

        foreach($urlsTab as $val){
            if( substr($val, -4) === '.css' ){
                $sub = substr($val, 0, 7);
                if( 'http://' ===  $sub || 'https:/' ===  $sub ){
                    if($this->getDomaine($val) === $this->url)
                        $this->ScannedUrlsTab[] = $val;
                    ladybug_dump($val);
                }
                else{
                    if($val[0] === '/')
                        $this->ScannedUrlsTab[] = $this->url .  $val;
                    else
                        $this->ScannedUrlsTab[] = $this->url . '/' . $val;
                }

            }
        }
        return $this->ScannedUrlsTab;
    }

    public function grabExtrat($url){
        $this->url = $this->cleanUpUrl($url);
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        $this->url = $this->getDomaine($this->url);

        $this->addHostCss($crawler->filter('link[href]')->extract(array('href')));
        $this->addHost($crawler->filter('img[src]')->extract(array('src')));
        $this->addHost($crawler->filter('script[src]')->extract(array('src')));
        return $this->ScannedUrlsTab;
    }

    private function getDomaine($url){
        return parse_url($url, PHP_URL_SCHEME).'://'. parse_url($url, PHP_URL_HOST);
    }
} 