<?php

namespace App\DataFixtures;

use App\Entity\Discussion;
use App\Entity\Message;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ForumFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        
        for($i=1; $i<=10; $i++)
        {
            $users = [];
            $user = new Utilisateur();

            $user->setUsername($faker->name())
                ->setEmail($faker->email)
                ->setPassword("password");
            
            array_push($users, $user);
            
            $manager->persist($user);

            for($j=1; $j<=mt_rand(0, 4); $j++)
            {
                $discussion = new Discussion();
    
                $discussion->setTitre($faker->sentence())
                ->setCreateur($user)
                ->setQuestion($faker->text());

                $manager->persist($discussion);

                for ($k=0; $k <= mt_rand(0, 9); $k++)
                { 
                    $response = new Message();
                    
                    $response->setContenu($faker->text())
                        ->setDiscussion($discussion)
                        ->setSender($users[mt_rand(0, count($users)-1)]);
                    
                    $manager->persist($response);
                }
            }
        }

        $manager->flush();
    }
}
