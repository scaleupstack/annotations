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

use ScaleUpStack\Annotations\Annotation\UnknownAnnotation;
use ScaleUpStack\Annotations\DocBlockParser;
use ScaleUpStack\Annotations\Tests\TestCase;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\Annotation\UnknownAnnotation
 */
final class UnknownAnnotationTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct()
     * @covers ::tag()
     * @covers ::arguments()
     * @covers ::classNamespace()
     */
    public function it_can_be_constructed_and_provides_the_parameters_via_getters()
    {
        // given a tag, an arguments string, and a class namespace
        $tag = 'some-unknown-tag';
        $arguments = 'some content';
        $classNamespace = DocBlockParser::class;

        // when creating the UnknownAnnotation
        $annotation = new UnknownAnnotation($tag, $arguments, $classNamespace);

        // then the tag, the arguments, and the class namespace can be retrieved via the setters
        $this->assertSame($tag, $annotation->tag());
        $this->assertSame($arguments, $annotation->arguments());
        $this->assertSame($classNamespace, $annotation->classNamespace());
    }
}
