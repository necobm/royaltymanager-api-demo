<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Studio;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $fileLocator = new FileLocator(__DIR__."/data");
        
        try{
            
            $this->loadStudiosFixtures($fileLocator, $manager);

            $this->loadEpisodesFixtures($fileLocator, $manager);

            $manager->flush();
        }
        catch(FileLocatorFileNotFoundException $e){
            die($e->getMessage());
        }
        catch(\Exception $e){
            die($e->getMessage());
        }
        
    }

    private function loadStudiosFixtures(FileLocator $fileLocator, ObjectManager $manager): void
    {
        $jsonStudiosFile = $fileLocator->locate('studios.json');

        $jsonStudiosContent = file_get_contents($jsonStudiosFile);

        $studiosArray = ( $jsonStudiosContent !== false ) ? json_decode($jsonStudiosContent, true) : [];

        if( empty($studiosArray) || is_null($studiosArray) || ! isset($studiosArray['studios']) ){

            throw new \Exception("No data in studios.json file");    
        }

        foreach($studiosArray['studios'] as $studioData){
            $studio = new Studio();

            $studio->setName($studioData['name']);
            $studio->setGuid($studioData['id']);
            $studio->setPayment($studioData['payment']);

            $manager->persist($studio);

            $this->addReference($studio->getGuid(), $studio);
        }
    }

    private function loadEpisodesFixtures(FileLocator $fileLocator, ObjectManager $manager): void
    {
        $jsonEpisodesFile = $fileLocator->locate('episodes.json');

        $jsonEpisodesContent = file_get_contents($jsonEpisodesFile);

        $episodesArray = ( $jsonEpisodesContent !== false ) ? json_decode($jsonEpisodesContent, true) : [];

        if( empty($episodesArray) || is_null($episodesArray) || ! isset($episodesArray['episodes']) ){

            throw new \Exception("No data in episodes.json file");
        }

        foreach($episodesArray['episodes'] as $episodeData){
            $episode = new Episode();

            $episode->setGuid($episodeData['id']);
            $episode->setName($episodeData['name']);

            // Get RightsOwner

            $studioOwner = $this->getReference($episodeData['rightsowner']);

            if( empty($studioOwner) ){
                continue;
            }

            $episode->setRightsowner($studioOwner);

            $manager->persist($episode);
        }
    }
}
