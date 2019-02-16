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

namespace ScaleUpStack\Annotations\Tests\Annotation;

use ScaleUpStack\Annotations\Annotation\VarAnnotation;
use ScaleUpStack\Annotations\Tests\TestCase;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\Annotation\VarAnnotation
 */
final class VarAnnotationTest extends AbstractAnnotationTestCase
{
    protected $annotationClassName = VarAnnotation::class;

    protected $tag = 'var';

    protected $validArguments = [
        'int',
        'bool',
        '\DateTime',
        'DocBlockParser',
        '\PHPUnit\Framework\TestCase',
        'int[]',
        '1ClassWith2Numbers',
        'Class_With_Underscores',
    ];

    protected $invalidArguments = [
        'Class-With-Hyphen',
        'ClassWithBackslashAtTheEnd\\',
        'argument with spaces',
    ];
}
