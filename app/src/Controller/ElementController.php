<?php
/**
 * Element controller.
 */

namespace App\Controller;

use App\Entity\Element;
use App\Form\Type\ElementType;
use App\Service\ElementServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ElementController.
 */
#[Route('/element')]
class ElementController extends AbstractController
{
    /**
     * Element service.
     */
    private ElementServiceInterface $elementService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * ElementController constructor.
     *
     * @param ElementServiceInterface $elementService Element service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(ElementServiceInterface $elementService, TranslatorInterface $translator)
    {
        $this->elementService = $elementService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'element_index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->elementService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'element/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Element    $element    Element entity
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'element_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET'],
    )]
    public function show(Element $element, Request $request): Response
    {
        $commentByElementPagedList = $this->elementService->createCommentByElementPaginatedList(
            $request->query->getInt('page', 1),
            $element
        );

        return $this->render(
            'element/show.html.twig',
            [
                'element' => $element,
                'pagination' => $commentByElementPagedList,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'element_create',
        methods: 'GET|POST',
    )]
    #[IsGranted('CREATE')]
    public function create(Request $request): Response
    {
        $element = new Element();
        $form = $this->createForm(
            ElementType::class,
            $element,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('element_create'),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Element    $element    Element entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'element_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    #[IsGranted('EDIT')]
    public function edit(Request $request, Element $element): Response
    {
        $form = $this->createForm(
            ElementType::class,
            $element,
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/edit.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Element    $element    Element entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/delete',
        name: 'element_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'DELETE'],
    )]
    #[IsGranted('DELETE')]
    public function delete(Request $request, Element $element): Response
    {
        $form = $this->createForm(
            FormType::class,
            $element,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl(
                    'element_delete',
                    ['id' => $element->getId()]
                ),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->delete($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/delete.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }
}
