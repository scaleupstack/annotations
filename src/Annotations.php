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

namespace ScaleUpStack\Annotations;

use ScaleUpStack\Annotations\Annotation\UnknownAnnotation;

final class Annotations
{
    private $annotations = [];

    public function add(string $tag, string $arguments, string $classNamespace)
    {
        $this->annotations[$tag][] = new UnknownAnnotation($tag, $arguments, $classNamespace);
    }

    public function annotationsByTag(string $tag) : array
    {
        if (! array_key_exists($tag, $this->annotations)) {
            return [];
        }

        return $this->annotations[$tag];
    }
}
