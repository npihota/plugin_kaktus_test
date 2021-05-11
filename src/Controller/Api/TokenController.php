<?php

declare(strict_types=1);


namespace App\Controller\Api;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @Route("api/login")
 * Class TokenController
 *
 * @package App\Controller\Api
 * @author Polvanov Igor <igor@zima.kg>
 * @copyright 2021 (c) Zima
 */
class TokenController extends AbstractController
{

    /**
     * @Route("", name="create_token", methods={"POST"})
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function createToken(Request $request, UserPasswordEncoderInterface $encoder, JWTEncoderInterface $manager): Response
    {
        $body = json_decode($request->getContent(), true);
        $name = $body['name'];
        $password = $body['password'];

        $users =  $this->getDoctrine()->getRepository(User::class);

        if (!$user = $users->findOneBy(['username' => $name])) {
            throw new NotFoundHttpException(sprintf("User %s not found.", $name));
        }

        if (!$encoder->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Password not correct.');
        }


        $token = $manager->encode([$user->getUsername(), $password]);

        return new JsonResponse(['token' => $token,], Response::HTTP_OK);
    }
}