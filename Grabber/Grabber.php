<?php

namespace Nzo\GrabberBundle\Services;
use Goutte\Client;

class Grabber {

    private $url;
    private $notScannedUrlsTab = array();
    private $ScannedUrlsTab = array();
    private $extensionTab = array();
    public function __construct(){

    }

    public function graburls($url, $notScannedUrlsTab=null, $extensionTab=null){
        $this->url = $url;
        $this->notScannedUrlsTab = $notScannedUrlsTab;
        $this->extensionTab      = $extensionTab;
        $this->ScannedUrlsTab[]  = $this->url;

        $i=0;
        while(count($this->ScannedUrlsTab) > $i){
                $this->crawler($this->ScannedUrlsTab[$i]);
                $i++;
        }
        return $this->ScannedUrlsTab;
    }

    private function crawler($newUrl){
        $client = new Client();
        $crawler = $client->request('GET', $newUrl);

        foreach ($crawler->filter('a[href]')->links() as $domElement) {
            $lien = $this->cleanUpUrl($domElement->getUri());
            if( $this->testExistanceScanned($lien) && $this->testExistanceNotScanned($lien) && $this->testDomaine($lien) && $this->testExtension($lien) )
                array_push($this->ScannedUrlsTab, $lien);

        }
        return true;
    }

    private function testDomaine($lien){
        return substr($lien, 0, strlen($this->url)) === $this->url;
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
            if(substr($lien, -(strlen($extension)+1)) === '.'.$extension)
                return false;
        }
        return true;
    }

    private function cleanUpUrl($lien){
        return ( substr($lien, -1) === '#') ? substr($lien, 0, -1) : $lien;
    }


} 