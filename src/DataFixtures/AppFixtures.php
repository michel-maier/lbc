<?php

namespace App\DataFixtures;

use App\Core\Ads\Domain\AutomobileAd;
use App\Core\Ads\Domain\JobAd;
use App\Core\Ads\Domain\RealEstateAd;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $ads = [
             new JobAd("Php Developer", "Job add content"),
             new RealEstateAd("My House", "Description of My house"),
             new AutomobileAd("My car", "My beautiful car !", "F40", "Ferrari")
             ];
         foreach($ads as $ad) {
             $manager->persist($ad);
         }

        $manager->flush();
    }
}
