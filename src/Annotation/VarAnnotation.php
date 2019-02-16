<?php declare(strict_types = 1);

/**
 * This file is part of ScaleUpStack/Annotations.
 *
 * For the full copyright and license information, please view the README.md and LICENSE.md files that were distributed
 * with this source code.
 *
 * @copyright 2019 ScaleUpVentures GmbH, https://www.scaleupventures.com
 * @link      https://github.com/scaelupstack/annotations
 */

namespace ScaleUpStack\Annotations\Annotation;

use ScaleUpStack\Annotations\Assert;

final class VarAnnotation extends AbstractAnnotation
{
    public function __construct(string $tag, string $type)
    {
        // validated tag
        $this->validateTag($tag, 'var');

        // validate type
        $allowedChars = 'a-zA-Z0-9_';
        $pattern = sprintf(
            '/^[%s%s]*[%s](\[\])?$/',
            $allowedChars,
            preg_quote('\\'),
            $allowedChars
        );

        Assert::regex(
            $type,
            $pattern,
            'Invalid @var type declaration %s.'
        );

        parent::__construct($tag, $type);
    }
}
