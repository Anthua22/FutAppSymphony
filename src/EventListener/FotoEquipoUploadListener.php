<?php


namespace App\EventListener;


use App\Entity\Equipo;
use App\Helper\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;

class FotoEquipoUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader=$uploader;
    }

    private function uploadFile($entity){
        if(!$entity instanceof  Equipo){
            return;
        }

        $file = $entity->getFotoFile();

        if($file instanceof UploadedFile){
            $filename = $this->uploader->upload($file);
            $entity->setFoto($filename);
        }

    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Equipo){
            if($entity->getFoto()!==null && $entity->getFoto()!==''){

                unlink(__DIR__.'/../../public/uploads/fotos/'.$entity->getFoto());
            }
            $this->uploadFile($entity);
        }



    }



}