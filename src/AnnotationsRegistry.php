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

namespace ScaleUpStack\Annotations;

use ScaleUpStack\Annotations\Annotation\PropertyReadAnnotation;
use ScaleUpStack\Annotations\Annotation\UnknownAnnotation;
use ScaleUpStack\Annotations\Annotation\VarAnnotation;

final class AnnotationsRegistry
{
    private static $registeredAnnotations = [];

    /**
     * @param int $context
     *        One of Annotations::CONTEXT_*.
     */
    public static function register(string $tag, string $className, int $context) // : void
    {
        Assert::range(
            $context,
            1,
            3,
            'Invalid DocBlock context %s.'
        );

        if ([] ===  self::$registeredAnnotations) {
            self::initRegisteredAnnotations();
        }

        self::$registeredAnnotations[$context][$tag] = $className;
    }

    /**
     * @param int $context
     *        One of Annotations::CONTEXT_*.
     */
    public static function resolve(string $tag, int $context) : string
    {
        Assert::range(
            $context,
            1,
            3,
            'Invalid DocBlock context %s.'
        );

        if ([] === self::$registeredAnnotations) {
            self::initRegisteredAnnotations();
        }

        $className = UnknownAnnotation::class;

        if (array_key_exists($tag, self::$registeredAnnotations[$context])) {
            $className = self::$registeredAnnotations[$context][$tag];
        }

        return $className;
    }

    private static function initRegisteredAnnotations() // : void
    {
        self::$registeredAnnotations = [
            Annotations::CONTEXT_CLASS => [
                'property-read' => PropertyReadAnnotation::class,
            ],
            Annotations::CONTEXT_PROPERTY => [
                'var' => VarAnnotation::class,
            ],
            Annotations::CONTEXT_METHOD => [
            ],
        ];
    }
}
