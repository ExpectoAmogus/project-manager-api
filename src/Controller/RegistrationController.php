<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private UserRepository $userRepository;

    public function __construct(EmailVerifier  $emailVerifier,
                                UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userRepository = $userRepository;
    }

    #[Route('/api/v1/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());
        $user = $this->userRepository->create($jsonData);
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('kirakanta24@gmail.com', 'Project Manager Mail Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        // do anything else you need here, like send an email

        return $this->json(['message' => 'Email verification sent'], Response::HTTP_CREATED);
//    }
    }

    #[Route('/api/v1/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {

            return $this->json(['message' => 'Error verifying email' . $exception->getReason()], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['message' => 'Email verified'], Response::HTTP_OK);
    }
}
