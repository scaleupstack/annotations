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

namespace ScaleUpStack\Annotations\Tests\PhpUnit;

use ScaleUpStack\Annotations\Annotation\MethodAnnotation;
use ScaleUpStack\Annotations\Annotation\PropertyReadAnnotation;
use ScaleUpStack\Annotations\Annotation\UnknownAnnotation;
use ScaleUpStack\Annotations\Annotation\VarAnnotation;
use ScaleUpStack\Annotations\Annotations;
use ScaleUpStack\Annotations\AnnotationsRegistry;
use ScaleUpStack\Annotations\InvalidArgumentException;
use ScaleUpStack\Annotations\Tests\Annotation\CustomAnnotation;
use ScaleUpStack\Annotations\Tests\Resources\TestCase;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\AnnotationsRegistry
 */
final class AnnotationsRegistryTest extends TestCase
{
    public function data_provider_of_predefiend_annotations()
    {
        return [
            ['not-handled', Annotations::CONTEXT_CLASS, UnknownAnnotation::class],
            ['var', Annotations::CONTEXT_PROPERTY, VarAnnotation::class],
            ['property-read', Annotations::CONTEXT_CLASS, PropertyReadAnnotation::class],
            ['method', Annotations::CONTEXT_CLASS, MethodAnnotation::class],
        ];
    }

    /**
     * @test
     * @dataProvider data_provider_of_predefiend_annotations
     * @covers ::resolve()
     * @covers ::initRegisteredAnnotations()
     */
    public function it_has_some_predefined_annotations(string $tag, int $context, string $expectedClassName)
    {
        // given a class name and a context as provided by the data provider

        // when resolving the tag in some context
        $className = AnnotationsRegistry::resolve($tag, $context);

        // then the class name is as expected
        $this->assertSame($expectedClassName, $className);
    }

    /**
     * @test
     * @covers ::resolve()
     */
    public function it_throws_an_exception_in_case_of_an_invalid_context()
    {
        // given an invalid context
        $context = -4;

        // when resolving a tag for that context
        // then an exception is thrown
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid DocBlock context -4.');

        AnnotationsRegistry::resolve('var', $context);
    }

    /**
     * @test
     * @covers ::register()
     */
    public function it_registers_custom_annotations_and_resolves_them()
    {
        // given a tag, a class name, and a context
        $tag = 'custom';
        $className = CustomAnnotation::class;
        $context = Annotations::CONTEXT_PROPERTY;

        // when registering the custom annotation
        AnnotationsRegistry::register($tag, $className, $context);

        // then it resolves for the correct context but returns the default annotation for other contexts
        $this->assertSame(
            $className,
            AnnotationsRegistry::resolve($tag, $context)
        );
        $this->assertSame(
            UnknownAnnotation::class,
            AnnotationsRegistry::resolve($tag, Annotations::CONTEXT_CLASS)
        );
    }

    /**
     * @test
     * @covers ::register()
     */
    public function it_throws_an_exception_when_registering_for_an_invalid_context()
    {
        // given an invalid context
        $context = -4;

        // when registering a custom annotation for that invalid context
        // then an exception is thrown

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid DocBlock context -4.');

        AnnotationsRegistry::register('var', CustomAnnotation::class, $context);
    }

    public function tearDown()
    {
        $reflectionProperty = new \ReflectionProperty(AnnotationsRegistry::class, 'registeredAnnotations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue([]);
    }
}
