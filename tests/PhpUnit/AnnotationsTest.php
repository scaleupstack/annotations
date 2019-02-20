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

namespace ScaleUpStack\Annotations\Tests\PhpUnit;

use ScaleUpStack\Annotations\Annotation\UnknownAnnotation;
use ScaleUpStack\Annotations\Annotations;
use ScaleUpStack\Annotations\Tests\Resources\TestCase;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\Annotations
 */
final class AnnotationsTest extends TestCase
{
    /**
     * @test
     * @covers ::add()
     * @covers ::annotationsByTag()
     */
    public function it_can_add_and_retrieve_annotations()
    {
        // given a tag, and an arguments string
        $tag = 'unknown';
        $arguments = 'some arguments string';
        // and an Annotations collection
        $collection = new Annotations();

        // when adding two annotations' information of the same tag to the Annotations collection
        $collection->add($tag, $arguments);
        $collection->add($tag, 'some other arguments');
        // and adding another unrelated annotation's information (i.e. an annotation with another tag)
        $collection->add('othertag', 'other arguments string');

        // then the corresponding annotations of a tag can be retrieved from the Annotations collection
        $annotations = $collection->annotationsByTag($tag);
        // and only the relevant annotations are returned
        $this->assertCount(2, $annotations);
        // and the returned annotations have the correct information
        $this->assertEquals(
            new UnknownAnnotation($tag, $arguments),
            $annotations[0]
        );
    }

    /**
     * @test
     * @covers ::annotationsByTag()
     */
    public function it_returns_an_empty_array_when_no_annotation_of_a_tag_is_available()
    {
        // given an Annotations collection
        $collection = new Annotations();

        // when trying to retrieve annotions of an unknown tag
        $annotations = $collection->annotationsByTag('notavailable');

        // then an empty array is returned
        $this->assertSame([], $annotations);
    }
}
