<?php

namespace Nzo\GrabberBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * GrabberBundle.
 *
 * @author Ala Eddine Khefifi <alakhefifi@gmail.com>
 * Website   www.alakhefifi.com
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nzo_grabber');

        return $treeBuilder;
    }
}
