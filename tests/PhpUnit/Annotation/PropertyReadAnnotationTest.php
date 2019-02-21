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

namespace ScaleUpStack\Annotations\Tests\PhpUnit\Annotation;

use ScaleUpStack\Annotations\Annotation\PropertyReadAnnotation;
use ScaleUpStack\Annotations\Tests\Resources\Annotation\AbstractAnnotationTestCase;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\Annotation\PropertyReadAnnotation
 */
final class PropertyReadAnnotationTest extends AbstractAnnotationTestCase
{
    protected $annotationClassName = PropertyReadAnnotation::class;

    protected $tag = 'property-read';

    protected $validArguments = [
        ['$myProperty', null, 'myProperty'],
        ['$myProperty', null, 'myProperty'],
        ['$myProperty Some Description', null, 'myProperty'],
        ['int $myProperty', 'int', 'myProperty'],
        ['int $myProperty Some description', 'int', 'myProperty'],
        ['int[] $myProperty', 'int[]', 'myProperty'],
        ['$myProperty   Some Description', null, 'myProperty'],
        ['int    $myProperty', 'int', 'myProperty'],
        ['int[]    $myProperty     Some description', 'int[]', 'myProperty'],
        ['\Some\Namespace\Class[] $myProperty', '\Some\Namespace\Class[]', 'myProperty'],
    ];

    protected $invalidArguments = [
        'Some descripton',
        'int',
        'int Some description',
        'int$myProperty',
    ];

    /**
     * @test
     * @dataProvider data_provider_of_valid_arguments
     * @covers ::dataType()
     * @covers ::propertyName()
     */
    public function it_extracts_datatay_and_property_name_from_arguments_string(
        string $arguments,
        ?string $expectedDatatype,
        string $expectedPropertyName
    )
    {
        // given an arguments string as provided via the data provider

        // when creating the property
        $annotation = new PropertyReadAnnotation('property-read', $arguments);

        // then datatype and property name are extracted
        $this->assertSame($expectedDatatype, $annotation->dataType());
        $this->assertSame($expectedPropertyName, $annotation->propertyName());
    }
}
