<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceController extends AbstractController
{
    /**
     * @Route("/conference", name="conference")
     */
    public function index(PostRepository $repoPost): Response
    {

        $posts = $repoPost->findAll();
       // dd($posts);
        $controller_name = 'ConferenceController';

        return $this->render('conference/index.html.twig', compact('posts', 'controller_name'));
    }
    /**
     * @Route("/conference/{id<[0-9]+>}", name="conference.show")
     */
    public function show(Post $post): Response
    {
        dd($post);
        return new Response;
    }
    /**
     * @Route("/conference/create", name="conference.create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /* if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('token');

            // 'delete-item' is the same value used in the template to generate the token
            if ($this->isCsrfTokenValid('create_post', $submittedToken)) {
                $data =  $request->request->all();
                $post = new Post;
                $post->setTitre($data['titre']);
                $post->setContenu($data['contenu']);
                $post->setDate(new \Datetime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                return $this->redirectToRoute('conference');
            }
          
        }*/
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('titre', TextType::class, ['label' => 'Titre'] , [
                'label_attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('contenu', TextareaType::class, ['label' => 'Contenu'], [
                'label_attr' => [
                    'class' => 'form-control',
                ],
            ])
            //->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setDate(new \Datetime());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('conference');
        }


        return $this->render('conference/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
