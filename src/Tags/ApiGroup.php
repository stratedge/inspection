<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiGroup extends BaseTag implements StaticMethod
{
    protected $name = 'api-group';

    protected $group;
    
    public function __construct($group)
    {
        $this->group = $group;
    }

    public static function create($body)
    {
        Assert::string($body);

        $parts = preg_split('/(\n+)/Su', $body, 2);

        if (!empty($parts[0])) {
            $group = array_shift($parts);
        }

        return new static($group);
    }


    public function __toString()
    {

    }


    public function getGroup()
    {
        return $this->group;
    
    }
    
    
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }
}
