<?php

declare(strict_types=1);


namespace App\Controller\Web;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $articles;

    public function __construct(ArticleRepository $articles)
    {
        $this->articles = $articles;
    }

    /**
     * @Route("/show/{id}", name="article_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $authorArticles = $this->articles->getArticlesByAuthorId($id);

        return $this->render('article/show.html.twig',
            ['articles' => $authorArticles]
        );
    }
}