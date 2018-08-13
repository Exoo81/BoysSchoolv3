<?php
namespace Gallery\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Gallery\Controller\GalleryController;
use Gallery\Service\GalleryBlogManager;
use User\Service\SeasonManager;


/**
 * This is the factory for GalleryController. Its purpose is to instantiate the
 * controller.
 */
class GalleryControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $galleryBlogManager = $container->get(GalleryBlogManager::class);
        
        // Instantiate the controller and inject dependencies
        return new GalleryController($entityManager, $galleryBlogManager);
    }
}

