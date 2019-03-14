<?php declare(strict_types = 1);

/**
 * This file is part of ScaleUpStack/Annotations
 *
 * For the full copyright and license information, please view the README.md and LICENSE.md files that were distributed
 * with this source code.
 *
 * @copyright 2019 - present ScaleUpVentures GmbH, https://www.scaleupventures.com
 * @link      https://github.com/scaleupstack/annotations
 */

namespace ScaleUpStack\Annotations\Annotation;

use ScaleUpStack\Annotations\Assert;

final class MethodAnnotation extends AbstractAnnotation
{
    private $returnType;

    private $methodName;

    private $parameters;

    public function __construct(string $tag, string $arguments)
    {
        // validate tag
        $this->validateTag($tag, 'method');

        parent::__construct($tag, $arguments);

        // validate arguments string and extract information

        // extract return type, method name and (yet) unprocessed parameters string
        $pattern = '/^' .
                        '(' .                               // optional return type plus one or more spaces
                            '(' . self::PATTERN_DATA_TYPE . ')[ ]+' .
                        ')?' .
                        self::PATTERN_METHOD_NAME . '\(' .  // method name plus opening bracket
                            '(.*)' .                        // anything till the closing bracket
                        '\)' .                              // closing bracket
                    '$/';

        Assert::regex($arguments, $pattern, 'Invalid @method type declaration %s.');

        preg_match($pattern, $arguments, $matches);

        $this->returnType = $matches[2] ?: null;
        $this->methodName = $matches[5];

        // extract parameters from unprocessed parameters string
        $parameters = $this->parseParametersString($matches[6]);

        Assert::notNull(
            $parameters,
            sprintf(
                'Invalid @method type declaration "%s".',
                $arguments
            )
        );

        $this->parameters = $parameters;
    }

    public function returnType() : ?string
    {
        return $this->returnType;
    }

    public function methodName() : string
    {
        return $this->methodName;
    }

    public function parameters() : array
    {
        return $this->parameters;
    }

    private function parseParametersString(string $parametersString) : ?array
    {
        $parameterPattern = '/^' .
                                '((' . self::PATTERN_DATA_TYPE . ') )?' .   // optional datatype plus space
                                '(' . self::PATTERN_VARIABLE_NAME . ')' .   // parameter name
                                '( = ' .                                    // optional default value
                                    '(' .
                                        '"([^"]|\\\\")*"' . '|' .           // in double quotes, or
                                        "'([^']|\\\\')*'" . '|' .           // in single quotes, or
                                        '[^"\', ]+' .                       // without quotes, e.g. numbers or null
                                    ')' .
                                ')?' .
                            '($|, (.+)$)/';                                 // end or <comma space and rest till end>

        $parameters = [];
        while ($parametersString) {
            $count = preg_match($parameterPattern, $parametersString, $matches);
            if (1 !== $count) {
                return null;
            }

            $parameterName = $matches[6];
            $dataType = $matches[2] ?: null;
            $hasDefaultValue = '' !== $matches[8];
            $parameters[$parameterName] = [
                'dataType' => $dataType,
                'hasDefaultValue' => $hasDefaultValue,
            ];

            if ($hasDefaultValue) {
                $parameters[$parameterName]['default'] = $matches[8];
            }

            $parametersString = array_key_exists(12, $matches) ? $matches[12] : '';
        }

        return $parameters;
    }
}