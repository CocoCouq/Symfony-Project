<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Environment
     */
    private $env;


    /**
     * SecurityController constructor.
     * @param UserRepository $repository
     * @param EntityManagerInterface $manager
     * @param Environment $env
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $manager, Environment $env)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->env = $env;
    }


    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/login/signin", name="signin")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function newUser(Request $request, MailerInterface $mailer): Response
    {
        $nUser = new User();
        $form = $this->createForm(UserType::class, $nUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->manager->persist($nUser);
            $this->manager->flush();
            dump($_POST['pwd_conf']);
            $email = (new Email())
                ->from('no_reply@starworms.com')
                ->to($nUser->getEmail())
                ->subject('Validation d\'Email')
                ->html($this->env->render('security/mails/Confirm/confirm.html.twig', [
                    'user' => $nUser
                ]));
            $mailer->send($email);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/newUser.html.twig', [
            'user' => $nUser,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/login/signin/verif/{username}/{userToken}", name="valid.login.email", methods="GET|POST")
     * @param User $user
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function validUser(User $user, Request $request): Response
    {
        $time = new DateTime();
        $mailToken = $request->attributes->get('userToken');
        $userToken = $user->getUserToken();
        $usermail = $request->attributes->get('username');
        $username = $user->getUsername();

        if($userToken == $mailToken && $usermail == $username)
        {
            $user->setRolesValid();
            $this->manager->flush();
            $message = 'Votre email a été validé';
        }
        else
        {
            $message = 'Mauvais chemin ?';
        }
        return $this->render('security/verifMail.html.twig', [
            'user' => $user,
            'message' => $message
        ]);
    }
}
