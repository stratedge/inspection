<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiScope extends BaseTag implements StaticMethod
{
    protected $name = 'api-scope';

    protected $scope;

    public function __construct($scope)
    {
        Assert::isArray($scope);

        $this->scope = $scope;
    }

    public static function create($body)
    {
        Assert::string($body);

        $scope = preg_split('/(,?\s+)/Su', $body);

        $scope = array_filter($scope);

        return new static($scope);
    }


    public function __toString()
    {

    }


    public function getScope()
    {
        return $this->scope;
    
    }
    
    
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }
}
