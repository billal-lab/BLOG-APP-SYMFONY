<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * le service gerant l'upload des fichiers
 */
class FileUploader
{


    /**
     * le dossier ou on veut mettre les fichier uploadé
     * @var String
     */
    private $targetDirectory;

    /**
     * @var SluggerInterface
     */
    private $slugger;



    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }



    /**
     * @param UploadedFile le fichier a uploaded
     */
    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            echo $e;
            die();
        }
        return $fileName;
    }


    
    /**
     * @return $this->targetDirectory
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}