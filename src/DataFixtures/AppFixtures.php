<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;
use App\Entity\Recipe;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Création des ingrédients
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                    ->setPrice(mt_rand(1, 199));

            $manager->persist($ingredient);
            $ingredients[] = $ingredient; // Stocke les ingrédients pour les recettes
        }

        // Création des recettes
        for ($i = 1; $i <= 20; $i++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->sentence(3))
                ->setTime(mt_rand(5, 120)) // Temps en minutes
                ->setNbPersons(mt_rand(1, 10))
                ->setDifficulty(mt_rand(1, 5))
                ->setDescription($this->faker->text(200))
                ->setPrice(mt_rand(1, 100))
                ->setIsFavorite($this->faker->boolean())
                ->setCreatedAt(new \DateTimeImmutable());

            // Ajout de quelques ingrédients à la recette
            for ($j = 0; $j < mt_rand(2, 5); $j++) {
                $recipe->addIngredient($ingredients[array_rand($ingredients)]);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
