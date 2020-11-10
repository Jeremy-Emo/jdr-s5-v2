<?php

namespace App\AbstractClass;

use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

abstract class AbstractScenario
{
    protected EntityManagerInterface $manager;
    protected UrlGeneratorInterface $urlGenerator;
    protected ?Environment $twig = null;
    protected LoggerInterface $logger;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param Environment $twig
     * @param LoggerInterface $logger
     * @required
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        $this->manager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @param string $route
     * @param array $parameters
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $route, array $parameters = []): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate($route, $parameters), 302);
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return Response
     * @throws ScenarioException
     */
    protected function renderNewResponse(string $view, array $parameters = []): Response
    {
        try {
            return (new Response())->setContent($this->twig->render($view, $parameters));
        } catch (\Exception $e) {
            throw new ScenarioException($e->getMessage());
        }
    }
}