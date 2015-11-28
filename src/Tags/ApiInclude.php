<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiInclude extends BaseTag implements StaticMethod
{
    protected $name = 'api-include';

    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public static function create(
        $body,
        Context $context = null
    ) {
        Assert::string($body);

        $parts = preg_split('/(\s+)/Su', $body, 1);

        if (!empty($parts[0])) {
            $key = array_shift($parts);
        } else {
            $key = null;
        }

        return new static($key);
    }


    public function getKey()
    {
        return $this->key;
    
    }
    
    
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }


    public function __toString()
    {

    }
}
