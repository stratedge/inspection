<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

class ApiParam extends BaseTag implements StaticMethod
{
    protected $name = 'api-param';

    protected $field;
    protected $group;
    protected $type;
    protected $size;
    protected $optional;
    protected $default_value;
    protected $description;

    public function __construct(
        $field,
        $group = null,
        $type = null,
        $size = null,
        $optional = false,
        $default_value = null,
        Description $description = null
    ) {
        $this->field = $field;
        $this->group = $group;
        $this->type = $type;
        $this->size = $size;
        $this->optional = $optional;
        $this->default_value = $default_value;
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
        $size = null;
        $optional = null;
        $default_value = null;
        $description = null;

        //Is the first part our group?
        if (preg_match('/^\([^)]*\)/', $body) !== 0) {
            $parts = preg_split('/\)\s*/', $body, 2);
            $group = substr($parts[0], 1);
            $body = $parts[1];
        }

        $parts = preg_split('/(\s+)/Su', $body, null, PREG_SPLIT_DELIM_CAPTURE);

        

        //Is the first part our type?
        if (preg_match('/{([^{]+)(?:{([^}]+)})?}/', $parts[0], $matches) !== 0) {
            array_shift($parts);
            array_shift($parts);

            if (!empty($matches[1])) {
                $type = $matches[1];
            } else {
                $type = null;
            }

            if (!empty($matches[2])) {
                $size = $matches[2];
            } else {
                $size = null;
            }
        }

        $field = array_shift($parts);
        array_shift($parts);

        if (strpos($field, '?') === 0) {
            $optional = true;
            $field = substr($field, 1);
        } else {
            $optional = false;
        }

        if (strpos($field, '=') !== false) {
            list($field, $default_value) = preg_split('/=/', $field, 2);
        } else {
            $default_value = null;
        }

        $description = $description_factory->create(implode('', $parts), $context);

        return new static(
            $field,
            $group,
            $type,
            $size,
            $optional,
            $default_value,
            $description
        );
    }


    public function __toString()
    {

    }


    public function getParam()
    {
        return [
            'field' => $this->field,
            'group' => $this->group,
            'type' => $this->type,
            'size' => $this->size,
            'optional' => $this->optional,
            'default_value' => $this->default_value,
            'description' => (string) $this->description
        ];
    }


    public function getGroup()
    {
        return $this->group;
    }
}
