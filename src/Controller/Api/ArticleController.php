<?php

declare(strict_types=1);


namespace App\Controller\Api;


use App\DTO\ArticleDTO;
use App\Entity\Article;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/article")
 * Class ArticleController
 *
 * @package App\Api\Controller
 * @author Polvanov Igor <igor@zima.kg>
 * @copyright 2021 (c) Zima
 */
class ArticleController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ArticleController constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @Route("/new", name="article_new", methods={"POST"})
     *
     * @param Request          $request
     * @param AuthorRepository $authorRepository
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return Response
     */
    public function new(Request $request, AuthorRepository $authorRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $serializer = $this->container->get('serializer');
        $articleView = $serializer->deserialize($request->getContent(), ArticleDTO::class, 'json');

        try {
            $errors = $this->validator->validate($articleView);
            $authors = $articleView->authors;

            if (count($errors) > 0) {
                $messages = [];
                foreach ($errors as $violation) {
                    $messages[$violation->getPropertyPath()][] = $violation->getMessage();
                }
                new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
            }

            $article = new Article($articleView->title, $articleView->createdAt);

            //create authors if not exist and add to article
            if ($this->hasRequestAuthors($authors)) {
                foreach ($authors as $authorName) {

                    if (!$authorRepository->hasAuthor($authorName)) {
                        $author = new Author($authorName);
                        $article->addAuthor($author);
                        continue;
                    }

                    $article->addAuthor($authorRepository->getAuthorByName($authorName));
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

        } catch (ValidationFailedException $e) {
            return new JsonResponse(['errors' => $e->getValue()], Response::HTTP_BAD_REQUEST);
        }


        return new JsonResponse(['created'], Response::HTTP_OK);
    }

    /**
     * @param array $authors
     *
     * @return bool
     */
    private function hasRequestAuthors(array $authors): bool
    {
        return !empty($authors);
    }

}