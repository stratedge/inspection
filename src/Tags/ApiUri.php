<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiUri extends BaseTag implements StaticMethod
{
    protected $name = 'api-uri';

    protected $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public static function create($body)
    {
        Assert::string($body);

        $parts = preg_split('/(\s+)/Su', $body, 2);

        if (!empty($parts[0])) {
            $uri = array_shift($parts);
        }

        return new static($uri);
    }


    public function __toString()
    {

    }


    public function getUri()
    {
        return $this->uri;
    
    }
    
    
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }
}
