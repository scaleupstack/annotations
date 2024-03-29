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

abstract class AbstractAnnotation implements AnnotationInterface
{
    const PATTERN_DATA_TYPE = '\\\\?' . // optional leading backslash
                              '(' . // namespace 0..*
                                  '[a-zA-Z_]' . '[a-zA-Z0-9_]*' . '\\\\' . // <letter, or underscore><letter, digit, or underscore>*<backslash>
                              ')*' .
                              '[a-zA-Z_]' . '[a-zA-Z0-9_]*' . // <letter, or underscore><letter, digit, or underscore>*
                              '(\[\])?'; // optional []

    const PATTERN_VARIABLE_NAME = '\\$([a-zA-Z_][a-zA-Z0-9_]*)'; // $<letter, or underscore><letter, digit, or underscore>*

    const PATTERN_METHOD_NAME = '([a-zA-Z_][a-zA-Z0-9_]*)'; // <letter, or underscore><letter, digit, or underscore>*

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
