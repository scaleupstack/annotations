<?php declare(strict_types = 1);

/**
 * This file is part of ScaleUpStack/Annotations.
 *
 * For the full copyright and license information, please view the README.md and LICENSE.md files that were distributed
 * with this source code.
 *
 * @copyright 2019 - present ScaleUpVentures GmbH, https://www.scaleupventures.com
 * @link      https://github.com/scaleupstack/annotations
 */

namespace ScaleUpStack\Annotations\Annotation;

use ScaleUpStack\Annotations\Assert;

final class VarAnnotation extends AbstractAnnotation
{
    public function __construct(string $tag, string $arguments)
    {
        // validated tag
        $this->validateTag($tag, 'var');

        // validate type
        $pattern = '/^' .
                        '(' . // union type
                            self::PATTERN_DATA_TYPE . preg_quote('|') .
                        ')*' .
                        self::PATTERN_DATA_TYPE .
                   '$/';
        Assert::regex($arguments, $pattern, 'Invalid @var type declaration %s.');

        parent::__construct($tag, $arguments);
    }
}
