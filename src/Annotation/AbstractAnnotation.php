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

use Webmozart\Assert\Assert;

abstract class AbstractAnnotation implements AnnotationInterface
{
    private $tag;

    private $arguments;

    public function __construct(string $tag, string $arguments)
    {
        $this->tag = $tag;
        $this->arguments = $arguments;
    }

    public function tag() : string
    {
        return $this->tag;
    }

    public function arguments() : string
    {
        return $this->arguments;
    }

    protected function validateTag(string $givenTag, string $expetedTag) : void
    {
        Assert::same(
            $expetedTag,
            $givenTag,
            'The tag of the annotation must be %1$s, but %2$s given.'
        );
    }
}
