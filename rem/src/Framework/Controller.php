<?php

declare(strict_types=1);
namespace Framework;
use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

abstract class Controller{

    protected Request $request;
    protected Response $response;
//    protected TemplateViewerInterface $viewer;
    protected mixed $viewer;
    protected $twig;

    public function setResponse(Response $response):void
    {
        $this->response = $response;

    }
    public function setRequest(Request $request):void{

        $this->request = $request;
    }
    public function setViewer(TemplateViewerInterface $viewer):void{

//        $this->viewer = $viewer;

        // Changed to Twig (not a TemplateViewerInterface

        $loader = new FilesystemLoader('../views/');
        $twig = new Environment($loader, [
             //'cache' => '/path/to/compilation_cache',
        ]);
        $this->viewer = $twig;

    }
    protected function view(string $template, array $data = []) : Response

    {

        $this->response->setBody($this->viewer->render($template, $data));

        return $this->response;
    }
    protected function redirect(string $url): Response
    {
        $this->response->redirect($url);

        return $this->response;
    }


}