<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiDescription extends BaseTag implements StaticMethod
{
    protected $name = 'api-description';

    protected $description;

    public function __construct(Description $description = null)
    {
        $this->description = $description;
    }

    public static function create(
        $body,
        DescriptionFactory $description_factory = null,
        Context $context = null
    ) {
        Assert::string($body);

        $description = $description_factory->create($body, $context);

        return new static($description);
    }


    public function __toString()
    {

    }


    public function getDescription()
    {
        return $this->description;
    }


    public function getDescriptionRendered()
    {
        return (string) $this->description->render();
    }
    
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
