<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiMethod extends BaseTag implements StaticMethod
{
    protected $name = 'api-method';

    protected $method;

    public function __construct($method)
    {
        $this->method = $method;
    }

    public static function create($body)
    {
        Assert::string($body);

        $parts = preg_split('/(\s+)/Su', $body, 2);

        if (!empty($parts[0])) {
            $method = array_shift($parts);
        }

        return new static($method);
    }


    public function __toString()
    {

    }


    public function getMethod()
    {
        return $this->method;
    
    }
    
    
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
}
