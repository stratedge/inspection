<?php

namespace Stratedge\Inspection\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

final class ApiVersion extends BaseTag implements StaticMethod
{
    protected $name = 'api-version';

    protected $major;
    protected $minor;
    protected $patch;

    public function __construct($major = 0, $minor = 0, $patch = 0)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    public static function create($body)
    {
        Assert::string($body);

        $parts = explode('.', $body);

        if (!empty($parts[0])) {
            Assert::integerish($parts[0]);
            $major = (int) array_shift($parts);
        } else {
            $major = 0;
        }

        if (!empty($parts[0])) {
            Assert::integerish($parts[0]);
            $minor = (int) array_shift($parts);
        } else {
            $minor = 0;
        }

        if (!empty($parts[0])) {
            Assert::integerish($parts[0]);
            $patch = (int) array_shift($parts);
        } else {
            $patch = 0;
        }

        return new static($major, $minor, $patch);
    }


    public function __toString()
    {

    }


    public function getVersion()
    {
        return [
            'major' => $this->major,
            'minor' => $this->minor,
            'patch' => $this->patch
        ];
    }
}
