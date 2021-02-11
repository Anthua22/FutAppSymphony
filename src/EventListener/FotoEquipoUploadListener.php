<?php


namespace App\EventListener;


use App\Entity\Chat;
use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\Usuario;
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
        if($entity instanceof Partido || $entity instanceof Chat){
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
        if( $entity instanceof Usuario && empty($entity->getFotoFile())){
            return;
        }
        if($entity instanceof Equipo || $entity instanceof Usuario){

            if($entity->getFoto()!==null && $entity->getFoto()!==''){

                unlink(__DIR__.'/../../public/uploads/fotos/'.$entity->getFoto());
            }
            $this->uploadFile($entity);
        }



    }



}