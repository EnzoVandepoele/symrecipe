<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'app_recipe')]
    public function index(RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/nouveau','recipe_new',methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés !'
            );

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/recipe/edit/{id}', 'recipe_edit',methods:['GET','POST'])]
    public function edit(Request $request,
        EntityManagerInterface $manager,
        Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $recipe = $form->getData();

            // $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés !'
            );

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
