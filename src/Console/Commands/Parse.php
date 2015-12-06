<?php

namespace Stratedge\Inspection\Console\Commands;

use Exception;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class Parse extends BaseCommand
{
    protected $templates = [];
    protected $nodes = [];

    protected $custom_tags = [
        'api-group' => \Stratedge\Inspection\Tags\ApiGroup::class,
        'api-method' => \Stratedge\Inspection\Tags\ApiMethod::class,
        'api-uri' => \Stratedge\Inspection\Tags\ApiUri::class,
        'api-title' => \Stratedge\Inspection\Tags\ApiTitle::class,
        'api-scope' => \Stratedge\Inspection\Tags\ApiScope::class,
        'api-desc' => \Stratedge\Inspection\Tags\ApiDescription::class,
        'api-description' => \Stratedge\Inspection\Tags\ApiDescription::class,
        'api-version' => \Stratedge\Inspection\Tags\ApiVersion::class,
        'api-template' => \Stratedge\Inspection\Tags\ApiTemplate::class,
        'api-param' => \Stratedge\Inspection\Tags\ApiParam::class,
        'api-header' => \Stratedge\Inspection\Tags\ApiHeader::class,
        'api-return' => \Stratedge\Inspection\Tags\ApiReturn::class,
        'api-throws' => \Stratedge\Inspection\Tags\ApiThrows::class,
        'api-include' => \Stratedge\Inspection\Tags\ApiInclude::class,
    ];

    protected function configure()
    {
        $this->setName('parse')
             ->setDescription('Parse docblocks into desired output')
             ->addOption(
                 'source',
                 's',
                 InputOption::VALUE_REQUIRED,
                 'The source directory to scan'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Determine source path
        if ($input->getOption('source')) {
            $source_path = realpath($input->getOption('source'));
        } else {
            $source_path = getcwd();
        }

        if (is_dir($source_path) == false || $source_path == false) {
            throw new \InvalidArgumentException(
                'The source directory is not a directory or cannot be found'
            );
        }

        if (!is_readable($source_path)) {
            throw new \InvalidArgumentException(sprintf(
                'The source directory "%s" is not readable',
                $source_path
            ));
        }

        //Determine output path
        $output_path = getcwd();

        $factory = DocBlockFactory::createInstance($this->custom_tags);

        $finder = new Finder();
        $finder->in($source_path)->name('*.php');

        foreach ($finder as $file) {
            if ($file->isReadable() == false) {
                continue;
            }

            $contents = file_get_contents($file->getRealPath());

            $tokens = token_get_all($contents);

            foreach ($tokens as $token) {
                if ($token[0] === T_DOC_COMMENT) {
                    try {
                        $docblock = $factory->create($token[1]);
                    } catch (Exception $e) {
                        continue;
                    }

                    if ($this->hasApiTags($docblock) == false) {
                        continue;
                    }

                    if ($this->isValidTemplate($docblock)) {
                        $tag = current($docblock->getTagsByName('api-template'));
                        $this->templates[$tag->getKey()] = $docblock;
                        continue;
                    }

                    if ($this->isValidNode($docblock)) {
                        $this->nodes[] = $docblock;
                        continue;
                    }
                }
            }
        }

        $data = [];

        foreach ($this->nodes as $node) {
            $data[] = $this->buildNodeData($node);
        }

        $file_path = $output_path . DIRECTORY_SEPARATOR . 'inspection_data.js';

        file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
    }


    protected function hasApiTags(DocBlock $docblock)
    {
        foreach ($docblock->getTags() as $tag) {
            if (strpos($tag->getName(), 'api-') === 0) {
                return true;
            }

            return false;
        }
    }


    protected function isValidTemplate(DocBlock $docblock)
    {
        return $docblock->hasTag('api-template') == true &&
               $docblock->hasTag('api-group') == false &&
               $docblock->hasTag('api-method') == false &&
               $docblock->hasTag('api-uri') == false;
    }


    protected function isValidNode(DocBlock $docblock)
    {
        return $docblock->hasTag('api-template') == false &&
               $docblock->hasTag('api-group') == true &&
               $docblock->hasTag('api-method') == true &&
               $docblock->hasTag('api-uri') == true;
    }


    protected function buildNodeData(DocBlock $node)
    {
        $data = [
            'group' => null,
            'method' => null,
            'uri' => null,
            'title' => null,
            'scope'=> [],
            'description' => null,
            'version' => [
                'major' => 0,
                'minor' => 0,
                'patch' => 0
            ],
            'params' => [
                'Default' => []
            ],
            'headers' => [],
            'returns' => [],
            'throws' => []
        ];

        $tags = [];

        foreach ($node->getTags() as $tag) {
            if ($tag->getName() === 'api-include') {
                $tags = array_merge($tags, $this->templates[$tag->getKey()]->getTags());
            } else {
                $tags[] = $tag;
            }
        }

        foreach ($tags as $tag) {
            switch ($tag->getName()) {
                case 'api-group':
                    $data['group'] = $tag->getGroup();
                    break;
                case 'api-method':
                    $data['method'] = $tag->getMethod();
                    break;
                case 'api-uri':
                    $data['uri'] = $tag->getUri();
                    break;
                case 'api-title':
                    $data['title'] = (string) $tag->getTitle();
                    break;
                case 'api-scope':
                    $data['scope'] = $tag->getScope();
                    break;
                case 'api-desc':
                case 'api-description':
                    $data['description'] = (string) $tag->getDescription();
                    break;
                case 'api-version':
                    $data['version'] = $tag->getVersion();
                    break;
                case 'api-param':
                    if (is_null($tag->getGroup())) {
                        $data['params']['Default'][] = $tag->getParam();
                    } else {
                        $data['params'][$tag->getGroup()][] = $tag->getParam();
                    }
                    break;
                case 'api-header':
                    $data['headers'][] = $tag->getParam();
                    break;
                case 'api-return':
                    $data['returns'][] = $tag->getReturn();
                    break;
                case 'api-throws':
                    $data['throws'][] = $tag->getThrows();
                    break;
            }
        }

        return $data;
    }
}
