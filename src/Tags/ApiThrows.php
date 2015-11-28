<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiThrows extends BaseTag implements StaticMethod
{
    protected $name = 'api-throws';

    protected $title;
    protected $code;
    protected $type;
    protected $description;

    public function __construct(
        $title,
        $code = null,
        $type = null,
        Description $description = null
    ) {
        $this->title = $title;
        $this->code = $code;
        $this->type = $type;
        $this->description = $description;
    }

    public static function create(
        $body,
        DescriptionFactory $description_factory = null,
        Context $context = null
    ) {
        Assert::string($body);

        $title = null;
        $code = null;
        $type = null;
        $description = null;

        $parts = preg_split('/(\s+)/Su', $body, 4, PREG_SPLIT_DELIM_CAPTURE);

        //Is the first part our code?
        if (preg_match('/\([^)]+\)/', $parts[0]) !== 0) {
            $code = substr(array_shift($parts), 1, -1);
            array_shift($parts);
        }

        //Is the first part our type?
        if (preg_match('/\{[^}]+\}/', $parts[0]) !== 0) {
            $type = substr(array_shift($parts), 1, -1);
            array_shift($parts);
        }

        $title = array_shift($parts);
        array_shift($parts);

        $description = $description_factory->create(implode('', $parts), $context);

        return new static($title, $code, $type, $description);
    }


    public function __toString()
    {

    }


    public function getThrows()
    {
        return [
            'title' => $this->title,
            'code' => $this->code,
            'type' => $this->type,
            'description' => (string) $this->description
        ];
    }
}
