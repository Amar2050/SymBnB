<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker      = Factory::create('fr_FR');

        //Creating user
        $users = [];
        $genres = ['male', 'female'];

        for ($i=0; $i <= 15 ; $i++) { 
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';
            
            $picture .= ($genre == 'male' ? 'men/' : 'women/').$pictureId;

            $hash = $this->encoder->encodePassword($user, "password");

            // if($genre =="male") $picture = $picture . 'men/'.$pictureId;
            // else $picture = $picture . 'women/' . $pictureId;

            // $picture .= "$gender/" . $pictureId;

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setPicture($picture)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                 ->setHash($hash);
                //  ->setSlug()

            $manager->persist($user);
            $users[] = $user ;   
        }

        //Create 30 ads 
        for ($i = 1; $i <= 30 ; $i++) { 

            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage =  $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>', $faker->paragraphs(5)).'</p>';
            $author= $users[mt_rand(0, count($users) -1)];

    
            $ad->setTitle($title)
               ->setCoverImage($coverImage)
               ->setIntroduction($introduction)
               ->setContent($content)
               ->setPrice(mt_rand(35,179))
               ->setRooms(mt_rand(1,5))
               ->setAuthor($author);

            // Create mutilple images in each Ad       
            for ($j=1; $j <= mt_rand(2, 5) ; $j++) { 
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);  
            }       
    
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
