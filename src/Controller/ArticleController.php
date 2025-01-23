<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAlLPublishedOrderByNewst();

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/news/{slug}")
     */
    public function show($slug, EntityManagerInterface $entityManager): Response
    {
        /** @var Article $articles */
        $articles = $entityManager->getRepository(Article::class)->findOneBy(['slug' => $slug]);

        if (!$articles) {
            throw $this->createNotFoundException(sprintf('Article with slug "%s" not found.', $slug));
        }

        return $this->render('article/show.html.twig', [
            'article' => $articles,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(string $slug, LoggerInterface $logger, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findOneBy(['slug' => $slug]);

        if (!$article) {
            return $this->json(['error' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        $article->incrementHeartCount();
        $entityManager->flush();

        $logger->info(sprintf('Article "%s" has been hearted.', $article->getTitle()));

        return $this->json([
            'hearts' => $article->getHeartCount(),
        ]);
    }
}
