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

interface AnnotationInterface
{
    public function __construct(string $tag, string $arguments, string $classNamespace);

    public function tag() : string;

    public function arguments() : string;

    public function classNamespace() : string;
}
