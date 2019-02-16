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
 * @coversDefaultClass \ScaleUpStack\Annotations\Annotation\AbstractAnnotation
 */
final class AbstractAnnotationTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct()
     * @covers ::tag()
     * @covers ::arguments()
     */
    public function it_can_be_constructed_and_provides_the_parameters_via_getters()
    {
        // given a tag, and an arguments string
        $tag = 'some-unknown-tag';
        $arguments = 'some content';

        // when creating the UnknownAnnotation
        $annotation = new UnknownAnnotation($tag, $arguments);

        // then the tag, and the arguments can be retrieved via the setters
        $this->assertSame($tag, $annotation->tag());
        $this->assertSame($arguments, $annotation->arguments());
    }
}
