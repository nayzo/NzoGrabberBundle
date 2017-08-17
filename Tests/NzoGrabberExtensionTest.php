<?php

/*
 * This file is part of the NzoGrabberBundle package.
 *
 * (c) Ala Eddine Khefifi <alakhefifi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nzo\GrabberBundle\Tests;

use Nzo\GrabberBundle\Grabber\Grabber;

class NzoGrabberExtensionTest extends \PHPUnit_Framework_TestCase
{
    const URL = 'https://www.facebook.com';
    /**
     * @var Grabber
     */
    private $grabber;

    public function setUp()
    {
        $this->grabber = $this->getMockBuilder(Grabber::class)
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @test
     */
    public function grabber()
    {
        $tableOfUrls = $this->grabber->grabUrlsNoRecursive(self::URL);
        $this->assertNotEmpty($tableOfUrls);

        $notScannedUrlsTab = $tableOfUrls[3];
        $tableOfUrls = $this->grabber->grabUrlsNoRecursive(self::URL, $notScannedUrlsTab);
        $this->assertNotContains($notScannedUrlsTab, $tableOfUrls);

        $exclude = substr($tableOfUrls[0], -3);
        $tableOfUrls = $this->grabber->grabUrlsNoRecursive(self::URL, $exclude, array('png'));
        $this->assertNotContains($notScannedUrlsTab, $tableOfUrls);

        $tableOfUrls = $this->grabber->grabUrlsNoRecursive(self::URL, null, array('png'));
        $this->assertNotEmpty($tableOfUrls);

        $img = $this->grabber->grabImg(self::URL);
        $this->assertNotEmpty($img);

        $js = $this->grabber->grabJs(self::URL);
        $this->assertNotEmpty($js);

        $css = $this->grabber->grabCss(self::URL);
        $this->assertNotEmpty($css);
    }
}
