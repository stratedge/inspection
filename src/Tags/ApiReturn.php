<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiReturn extends BaseTag implements StaticMethod
{
    protected $name = 'api-return';

    protected $field;
    protected $group;
    protected $type;
    protected $description;

    public function __construct(
        $field,
        $group = null,
        $type = null,
        Description $description = null
    ) {
        $this->field = $field;
        $this->group = $group;
        $this->type = $type;
        $this->description = $description;
    }

    public static function create(
        $body,
        DescriptionFactory $description_factory = null,
        Context $context = null
    ) {
        Assert::string($body);

        $field = null;
        $group = null;
        $type = null;
        $description = null;

        $parts = preg_split('/(\s+)/Su', $body, 4, PREG_SPLIT_DELIM_CAPTURE);

        //Is the first part our group?
        if (preg_match('/\([^)]+\)/', $parts[0]) !== 0) {
            $group = substr(array_shift($parts), 1, -1);
            array_shift($parts);
        }

        //Is the first part our type?
        if (preg_match('/\{[^}]+\}/', $parts[0]) !== 0) {
            $type = substr(array_shift($parts), 1, -1);
            array_shift($parts);
        }

        $field = array_shift($parts);
        array_shift($parts);

        $description = $description_factory->create(implode('', $parts), $context);

        return new static($field, $group, $type, $description);
    }


    public function __toString()
    {

    }


    public function getReturn()
    {
        return [
            'group' => $this->group,
            'type' => $this->type,
            'field' => $this->field,
            'description' => (string) $this->description
        ];
    }
}
