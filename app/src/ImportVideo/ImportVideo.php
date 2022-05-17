<?php

namespace App\ImportVideo;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportVideo
{
    protected $filename;
    protected $format;
    public $rows;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function read()
    {
        $this->rows = $this->readFile();
    }

    public function readFile()
    {
        return file_get_contents($this->filename);
    }

    public function prepare()
    {
        $decoder = new Serializer([new ObjectNormalizer()], [new YamlEncoder(), new XmlEncoder(), new JsonEncoder()]);
        $this->rows = $decoder->decode($this->rows, $this->format);
    }

    public function parse(){
        return true;
    }

    public function save()
    {
        foreach ($this->rows as $row){
            $video = new Video();
            $video->setName($row['name']);
            $video->setUrl($row['url']);
            $video->getTags($row['tags']);
            $this->entityManager->persist($video);
        }
        $this->entityManager->flush();
        return true;
    }
}