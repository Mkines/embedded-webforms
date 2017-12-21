<?php
// src/BCCustomSSOBundle/DependencyInjection/BCCustomSSOExtension.php
namespace BCCustomSSOBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class BCCustomSSOExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // This loader, loads in the services.yml and merges it with the main app's services container
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
