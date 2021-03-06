<?php

namespace Archive\Service;

use User\Entity\User;
use Application\Entity\NewsPost;
use Application\Entity\NewsBlog;
use Classes\Entity\Post;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Gallery\Entity\GalleryPost;


/**
 * This service is responsible for adding/editing etc. school life activit.
 * 
 */
class ArchiveManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    
    /**
     * Season manager.
     * @var User\Service\SeasonManager
     */
    private $seasonManager;
    
    private $currentSeason;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $seasonManager) {
        $this->entityManager = $entityManager;
        $this->seasonManager = $seasonManager;
        
        $this->currentSeason = $this->seasonManager->getCurrentSeason();
    }
    
    public function getAllPostFromCompletedSeason($paginated=false, $season){
        if ($paginated) {
            return $this->fetchPaginatedResults($season);
        }
        
        return $this->entityManager->getRepository(News::class)
                     ->findBy(['datePublished'=>'DESC']);
    }
    
    private function fetchPaginatedResults($season){
        
        
        // Get all news as query
        $news = $this->getAllNewsQuery($season);
        
        if($news->getResult() == null){
            return null;
        }

        $adapter = new DoctrineAdapter(new ORMPaginator($news, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(6);        
        return $paginator;
    }
    
    private function getAllNewsQuery($season){
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
       
            $queryBuilder->select('post')
                ->from(Post::class, 'post')
                ->innerJoin('post.season', 'season')                    
                ->andWhere('season.id = :seasonID')
                ->setParameter('seasonID', $season->getId())
                ->orderBy('post.datePublished', 'DESC');
           
            return $queryBuilder->getQuery();
        
        
    }
    
    public function getAllGalleriesCompletedSeason($paginated=false, $season){
        if ($paginated) {
            return $this->fetchPaginatedGalleryResults($season);
        }
        
        return $this->entityManager->getRepository(GalleryPost::class)
                     ->findBy(['datePublished'=>'DESC']);
    }
    
    private function fetchPaginatedGalleryResults($season){
        
        // Get all post as query
        $galleryPostList = $this->getAllGalleryQuery($season);
        
        if($galleryPostList->getResult() == null){
            return null;
        }
     
        $adapter = new DoctrineAdapter(new ORMPaginator($galleryPostList, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(6);
        
        return $paginator;
    }
    
    private function getAllGalleryQuery($season){
        
       $queryBuilder = $this->entityManager->createQueryBuilder();
       
            $queryBuilder->select('post')
                ->from(GalleryPost::class, 'post')
                  ->innerJoin('post.season', 'season')  
                
                ->andWhere('post.postGallery is not empty')
                ->orWhere('post.videoName IS NOT NULL')
                    
                ->andWhere('season.id = :seasonID')
                ->setParameter('seasonID', $season->getId())

                ->orderBy('post.datePublished', 'DESC');

            
            return $queryBuilder->getQuery();
        
    }
    
}

