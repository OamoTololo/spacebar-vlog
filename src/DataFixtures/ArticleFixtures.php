<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends AppFixtures
{
    private static $articleTitles = [
        'Why Asteroids Taste Like Bacon',
        'Life on Planet Mercury: Tan, Relaxing and Fabulous',
        'Light Speed Travel: Fountain of Youth or Fallacy',
    ];

    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];

    private static $articleAuthors = [
        'Oamogetswe Mgidi',
        'Tshimologo Mgidi',
        'Tiisetso Kutumela',
    ];
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany($manager, Article::class, function (Article $article, $count) use ($manager) {
            $article
                ->setTitle($this->faker->randomElement(self::$articleTitles))
                ->setContent(<<<EOF
Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
**turkey** shank eu pork belly meatball non cupim.
    
Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.

Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
fugiat.
EOF
                )
            ;

            $article
                ->setAuthor($this->faker->randomElement(self::$articleAuthors))
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFileName($this->faker->randomElement(self::$articleImages));

            // publish most articles
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
            }

            $comment1 = new Comment();
            $comment1->setAuthorName('Oamogetswe Mgidi');
            $comment1->setContent('I ate a normal rock once. It did not taste like bacon.');
            $comment1->setArticle($article);

            $manager->persist($comment1);

            $comment2 = new Comment();
            $comment2->setAuthorName('Tshimologo Mgidi');
            $comment2->setContent('Whoooo! I\'m going on an all-asteroids diet.');
            $comment2->setArticle($article);

            $manager->persist($comment2);

            $comment3 = new Comment();
            $comment3->setAuthorName('Tiisetso Kutumela');
            $comment3->setContent('I like bacon too! Buy some from my site! oamogetswemgidi.com');
            $comment3->setArticle($article);

            $manager->persist($comment3);


        });

        $manager->flush(); // calls the execute query
    }
}
