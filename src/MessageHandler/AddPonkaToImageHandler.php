<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoPonkaficator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Photo\PhotoFileManager;
use Doctrine\ORM\EntityManagerInterface;

class AddPonkaToImageHandler implements MessageHandlerInterface
{
    private PhotoPonkaficator $ponkaficator;
    private PhotoFileManager $photoManager;
    private EntityManagerInterface $entityManager;
    
    public function __construct(PhotoPonkaficator $ponkaficator, PhotoFileManager $photoManager, EntityManagerInterface $entityManager)
    {
        $this->ponkaficator = $ponkaficator;
        $this->photoManager = $photoManager;
        $this->entityManager = $entityManager;        
    }
    
    public function __invoke(AddPonkaToImage $addPonkaToImage)
    {
        $imagePost = $addPonkaToImage->getImagePost();
        
        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoManager->read($imagePost->getFilename())
        );
        $this->photoManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();
        $this->entityManager->flush();
    }
}