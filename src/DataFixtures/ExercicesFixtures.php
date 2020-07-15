<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Exercice;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Entity\Chapitre;

class ExercicesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=1; $i<=2; $i++)
        {
            $niveau = new Niveau();
            $niveau->setNom($faker->sentence());

            $manager->persist($niveau);

            for($j=1; $j<=mt_rand(4, 12); $j++)
            {
                $mat = new Matiere();
                $mat->setNom($faker->sentence())
                    ->setNiveau($niveau);

                $manager->persist($mat);
    
                for($k=1; $k<=mt_rand(4, 9); $k++)
                {
                    $chap = new Chapitre();
                    $chap->setNom($faker->sentence())
                        ->setMatiere($mat);

                    $manager->persist($chap);

                    for($n=1; $n<=mt_rand(9, 17); $n++)
                    {
                        $exo = new Exercice();

                        $enonce = '<p>' . join($faker->paragraphs(3), '</p><p>') . '</p>';
                        $solution = '<p>' . join($faker->paragraphs(3), '</p><p>') . '</p>';

                        $exo->setTitre($faker->sentence())
                            ->setEnonce($enonce)
                            ->setSolution($solution)
                            ->setChapitre($chap);
            
                        $manager->persist($exo);
                    } 
                }            
            }
        }

        $manager->flush();
    }
}
