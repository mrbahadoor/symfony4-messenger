<?php

namespace App\MessageHandler;

use App\Message\DeleteImagePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\DeletePhotoFile;

class DeleteImagePostHandler implements MessageHandlerInterface
{
    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;

    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $entityManager)
    {
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
    }
    
    public function __invoke(DeleteImagePost $deleteImagePost)
    {
        $imagePost = $deleteImagePost->getImagePost();
        $fileName = $imagePost->getFilename();
        
        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new DeletePhotoFile($fileName));
    }
}