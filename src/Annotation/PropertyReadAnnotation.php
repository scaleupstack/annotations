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

final class PropertyReadAnnotation extends AbstractAnnotation
{
    private $dataType;

    private $propertyName;

    public function __construct(string $tag, string $arguments)
    {
        // validate tag
        $this->validateTag($tag, 'property-read');

        // validate type
        $pattern = '/^' .
                     '(' . // optional datatype plus one or more spaces
                        '(' . self::PATTERN_DATA_TYPE . ')[ ]+' .
                     ')?' .
                     self::PATTERN_VARIABLE_NAME . // variable
                   '($| )/';  // end or at least one space

        Assert::regex($arguments, $pattern, 'Invalid @property-read type declaration %s.');

        preg_match($pattern, $arguments, $matches);

        parent::__construct($tag, $arguments);

        $this->dataType = $matches[2] ?: null;
        $this->propertyName = $matches[5];
    }

    public function dataType() : ?string
    {
        return $this->dataType;
    }

    public function propertyName() : string
    {
        return $this->propertyName;
    }
}
