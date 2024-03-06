<?php

namespace App\Controller\Front\Action;

use App\Controller\Front\FrontController;
use App\Entity\Core\Website;
use App\Entity\Layout\Block;
use App\Entity\Module\Story\Story;
use App\Form\Type\Module\Story\StoryType;
use App\Repository\Module\Story\StoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Cache\Exception\CacheException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * StoryController.
 *
 * Front Story render
 *
 * @author Sébastien FOURNIER <contact@sebastien-fournier.com>
 */
class StoryController extends FrontController
{
    /**
     * View.
     *
     * @Route("/front/story/form/{website}/{block}/{filter}", methods={"GET", "POST"}, name="front_story_form", schemes={"%protocol%"})
     *
     * @throws \Exception
     */
    public function form(Request $request, Website $website, Block $block = null, mixed $filter = null): JsonResponse|Response
    {
        $configuration = $website->getConfiguration();
        $websiteTemplate = $configuration->getTemplate();
        $template = 'front/'.$websiteTemplate.'/actions/story/form.html.twig';
        $form = $this->createForm(StoryType::class, new Story(), [
            'website' => $website,
            'colId' => $block instanceof Block ? $block->getCol()->getCustomId() : '',
        ]);
        $form->handleRequest($request);
        $arguments = [
            'formSuccess' => false,
            'configuration' => $configuration,
            'websiteTemplate' => $websiteTemplate,
            'website' => $website,
            'block' => $block,
            'form' => $form->createView(),
        ];
        if ($form->isSubmitted()) {
            $token = null;
            if ($form->isValid()) {
                /** @var Story $story */
                $story = $form->getData();
                $token = base64_encode(uniqid().password_hash($story->getEmail(), PASSWORD_BCRYPT).random_bytes(10));
                $token = substr(str_shuffle($token), 0, 30);
                $story->setWebsite($website);
                $story->setLocale($request->getLocale());
                $story->setToken($token);
                $this->coreLocator->em()->persist($story);
                $this->coreLocator->em()->flush();
            }
            $arguments['formSuccess'] = $form->isValid();

            return new JsonResponse([
                'success' => $form->isValid(),
                'redirection' => $this->generateUrl('front_story_form_success', ['website' => $website, 'token' => $token]),
                'showModal' => false,
                'html' => $this->renderView($template, $arguments),
            ]);
        }

        return $this->render($template, $arguments);
    }

    /**
     * Form success.
     *
     * @Route("/front/my-story/form/success", methods={"GET"}, name="front_story_form_success", schemes={"%protocol%"})
     */
    public function formSuccess(Request $request, StoryRepository $storyRepository): Response
    {
        $token = $request->get('token');
        $story = $token ? $storyRepository->findOneBy(['token' => $token]) : null;
        if (!$story) {
            return $this->redirectToRoute('front_index');
        }
        $story->setToken(null);
        $this->coreLocator->em()->persist($story);
        $this->coreLocator->em()->flush();

        $website = $this->getWebsite($request);
        $configuration = $website->getConfiguration();
        $template = $configuration->getTemplate();

        return $this->render('front/'.$template.'/actions/story/form-success.html.twig', [
            'website' => $website,
            'websiteTemplate' => $template,
        ]);
    }

    /**
     * View.
     *
     * @Route("/front/story/teaser", methods={"GET"}, name="front_story_teaser", schemes={"%protocol%"})
     *
     * @throws NonUniqueResultException|CacheException
     */
    public function teaser(Request $request, StoryRepository $storyRepository, Website $website, Block $block = null): Response
    {
        $stories = $storyRepository->findActivated($website, $request->getLocale());
        $configuration = $website->getConfiguration();
        $template = $configuration->getTemplate();
        return $this->render('front/'.$template.'/actions/story/teaser.html.twig', [
            'stories' => $stories,
            'website' => $website,
            'block' => $block,
            'websiteTemplate' => $template,
        ]);
    }
}