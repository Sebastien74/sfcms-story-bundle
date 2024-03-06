<?php

namespace App\Controller\Admin\Module\Story;

use App\Controller\Admin\AdminController;
use App\Entity\Module\Story\Story;
use App\Form\Type\Module\Story\StoryType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * StoryController.
 *
 * Story Action management
 *
 * @Route("/admin-%security_token%/{website}/stories", schemes={"%protocol%"})
 *
 * @author Sébastien FOURNIER <contact@sebastien-fournier.com>
 */
#[IsGranted('ROLE_STORY')]
#[Route('/admin-%security_token%/{website}/stories', schemes: '%protocol%')]
class StoryController extends AdminController
{
    protected ?string $class = Story::class;
    protected ?string $formType = StoryType::class;

    /**
     * Index Story.
     *
     * {@inheritdoc}
     */
    #[Route('/index', name: 'admin_story_index', methods: 'GET|POST')]
    public function index(Request $request, PaginatorInterface $paginator)
    {
        return parent::index($request, $paginator);
    }

    /**
     * New Story.
     *
     * {@inheritdoc}
     */
    #[Route('/new', name: 'admin_story_new', methods: 'GET|POST')]
    public function new(Request $request)
    {
        return parent::new($request);
    }

    /**
     * Edit Story.
     *
     * {@inheritdoc}
     */
    #[Route('/edit/{story}', name: 'admin_story_edit', methods: 'GET|POST')]
    public function edit(Request $request)
    {
        return parent::edit($request);
    }

    /**
     * Show Story.
     *
     * {@inheritdoc}
     */
    #[Route('/show/{story}', name: 'admin_story_show', methods: 'GET|POST')]
    public function show(Request $request)
    {
        return parent::show($request);
    }

    /**
     * Position Story.
     *
     * {@inheritdoc}
     */
    #[Route('/position/{story}', name: 'admin_story_position', methods: 'GET|POST')]
    public function position(Request $request)
    {
        return parent::position($request);
    }

    /**
     * Delete Story.
     *
     * {@inheritdoc}
     */
    #[Route('/delete/{story}', name: 'admin_story_delete', methods: 'DELETE')]
    public function delete(Request $request)
    {
        return parent::delete($request);
    }
}