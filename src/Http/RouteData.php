<?php

namespace LaravelSferaLibrary\Http;

class RouteData
{
    public string $httpMethod;
    public string $uri;
    public string $controllerClass;
    public string $methodName;

    /**
     * RouteData constructor.
     * @param string $httpMethod
     * @param string $uri
     * @param string $controllerClass
     * @param string $methodName
     */
    public function __construct(string $httpMethod, string $uri, string $controllerClass, string $methodName)
    {
        $this->httpMethod = $httpMethod;
        $this->uri = $uri;
        $this->controllerClass = $controllerClass;
        $this->methodName = $methodName;
    }
}