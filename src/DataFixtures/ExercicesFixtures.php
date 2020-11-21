<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Exercice;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Entity\Chapitre;
use App\Entity\Classe;

class ExercicesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=1; $i<=2; $i++)
        {
            $niveau = new Niveau();
            $niveau->setNom("Niveau $i");

            $manager->persist($niveau);

            for ($l=0; $l <= mt_rand(3, 4) ; $l++)
            { 
                $classe = new Classe();
                $classe->setNom("Classe $l")
                    ->setNiveau($niveau);

                $manager->persist($classe);

                for($j=1; $j<=mt_rand(4, 12); $j++)
                {
                    $mat = new Matiere();
                    $mat->setNom($faker->sentence())
                        ->setClasse($classe);

                    $manager->persist($mat);
        
                    for($k=1; $k<=mt_rand(4, 9); $k++)
                    {
                        $chap = new Chapitre();
                        $chap->setNom($faker->sentence())
                            ->setMatiere($mat);

                        $manager->persist($chap);
                    }            
                }
            }

        }

        $manager->flush();
    }
}
