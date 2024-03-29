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

namespace ScaleUpStack\Annotations;

use ScaleUpStack\Annotations\Annotation\UnknownAnnotation;

final class Annotations
{
    const CONTEXT_CLASS = 1;

    const CONTEXT_PROPERTY = 2;

    const CONTEXT_METHOD = 3;

    private $annotations = [];

    /**
     * @param int $context
     *        One of Annotations::CONTEXT_*
     */
    public function add(string $tag, string $arguments, int $context)
    {
        $className = AnnotationsRegistry::resolve($tag, $context);

        $this->annotations[$tag][] = new $className($tag, $arguments);
    }

    public function annotationsByTag(string $tag) : array
    {
        if (! array_key_exists($tag, $this->annotations)) {
            return [];
        }

        return $this->annotations[$tag];
    }
}
