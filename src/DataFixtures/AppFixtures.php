<?php

namespace App\DataFixtures;

use Generator;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{  

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Ingredients
        $ingredients = [];
        for($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word())
                ->setPrice(mt_rand(0, 100));

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recipes
        for ($j = 0; $j < 25; $j++) { 
            $recipe = new Recipe();
            $recipe->setName($faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($faker->text(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);
            
                for ($k = 0; $k  < mt_rand(5, 15); $k ++) { 
                    $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
                }
                $manager->persist($recipe);
        }

        // Users
        for ($i = 0; $i < 10; $i++) { 
            $user = new User();
            $user->setFullName($faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $faker->firstName() : null)
                ->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $manager->persist($user);
        }

        $manager->flush();
    }
}
