<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class that make controller lighter by making all calcul her rather in controller, You need to
 * 
 * set the entity wich you what to work on it
 */
class Pagination
{   
    /**
     * Name of the entity wich you what to paging 
     *
     * @var [string]
     */
    private $entityClass;

    /**
     * Number of entry that you want to display by page
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * The actual page
     *
     * @var integer
     */
    private $currentPage = 1;

    /**
     * Doctrine ORM using here for the repository of entity
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * Twig template engine who permit display of our pagination  
     *
     * @var Twig\Environment
     */
    private $twig;

    /**
     * Name of the route we use for navigation
     *
     * @var [string]
     */
    private $route;

    /**
     * Path of our template using service.yaml but you can customise it in controller if necessary
     *
     * @var [string]
     */
    private $templatePath;

    /**
     * Don't forget to configure service.yaml for the templatePath
     *
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param string $templatePath
     */
    public function __construct(ObjectManager $manager, Environment $twig, 
    RequestStack $request, $templatePath) {
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager      = $manager;
        $this->twig         = $twig;
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath() {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath() {
        return $this->templatePath;
    }

    public function setRoute($route) {
        $this->route = $route;
        
        return $this->route;
    }

    public function getRoute() {
        return $route;
    }

    public function display() {
        $this->twig->display($this->templatePath, [
            'page'  => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }


    public function getPages() {
        // handleling execption message
        if(empty($this->entityClass)) {
            throw new \Exception("You didn't set entityClass in with the pagination service . 
            Use method setEntityClass($EntityName::class) of Pagination.php ");
        }

        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getData() {
        // handleling execption message
        if(empty($this->entityClass)) {
            throw new \Exception("You didn't set entityClass in with the pagination service . 
            Use method setEntityClass($EntityName::class) of Pagination.php ");
        }
        // Calculate offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        // Ask repository to find elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        // Send elements
        return $data;
    }

    public function setPage($page) {
        $this->currentPage = $page;

        return $this;
    }

    public function getPage() {
        return $this->currentPage;
    }

    public function setLimit($limit) {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit($limit) {
        return $this->limit;
    }
    public function setEntityClass($entityClass) {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass($entityClass) {
        return $this->entityClass;
    }
}
