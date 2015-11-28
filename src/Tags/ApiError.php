<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiError extends BaseTag implements StaticMethod
{
    protected $name = 'api-error';

    protected $var;
    protected $type;
    protected $description;

    public function __construct($var, $type = null, Description $description = null)
    {
        $this->var = $var;
        $this->type = $type;
        $this->description = $description;
    }

    public static function create(
        $body,
        DescriptionFactory $description_factory = null,
        Context $context = null
    ) {
        Assert::string($body);

        $parts = preg_split('/(\s+)/Su', $body, 3);

        if (!empty($parts[0])) {
            $var = array_shift($parts);
        }

        if (!empty($parts[0])) {
            $type = array_shift($parts);
        }

        if (!empty($parts[0])) {
            $description = $description_factory->create($parts[0], $context);
        } else {
            $description = null;
        }

        return new static($var, $type, $size, $description);
    }


    public function __toString()
    {

    }
}
