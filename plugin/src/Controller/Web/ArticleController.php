<?php

namespace App\Controller\Web;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $articles;

    /**
     * ArticleController constructor.
     *
     * @param ArticleRepository  $articles
     */
    public function __construct(ArticleRepository $articles)
    {
        $this->articles = $articles;
    }


    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render(
            'article/index.html.twig',
            [
                'articles' => $articleRepository->findAll(),
            ]
        );
    }


}
