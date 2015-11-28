<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiTitle extends BaseTag implements StaticMethod
{
    protected $name = 'api-title';

    protected $title;

    public function __construct(Description $title = null)
    {
        $this->title = $title;
    }

    public static function create(
        $body,
        DescriptionFactory $description_factory = null,
        Context $context = null
    ) {
        Assert::string($body);

        $title = $description_factory->create($body, $context);

        return new static($title);
    }


    public function __toString()
    {

    }


    public function getTitle()
    {
        return $this->title;
    
    }


    public function getTitleRendered()
    {
        if (is_null($this->title)) {
            return null;
        } else {
            return $this->title->render();
        }
    
    }
    
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
