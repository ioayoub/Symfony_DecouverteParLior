<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Articles;
use App\Entity\Category;
use App\Entity\Comment;
use Faker\Factory;

class ArticleFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Creation des articles

        for ($i = 1; $i < 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph(3));

            $manager->persist($category);

            //Cration entre 4 et 6 articles
            for ($j = 1; $j < mt_rand(4, 6); $j++) {

                $content = '<p>' . $faker->paragraph(5) . '</p><p>' . "</p>";

                $article = new Articles();
                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($article);

                //Creation commentaires
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();

                    $content = '<p>' . $faker->paragraph(2) . '</p><p>' . "</p>";

                    //interval date

                    $interval =  (new \DateTime())->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $comment->setAuthor($faker->name())
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTime('-' . $days . ' days'))
                        ->setArticle($article);

                    $manager->persist($comment);
                }

                $manager->flush();
            }
        }
    }
}
