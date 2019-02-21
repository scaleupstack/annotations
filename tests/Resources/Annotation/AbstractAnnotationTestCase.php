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

namespace ScaleUpStack\Annotations\Tests\Resources\Annotation;

use ScaleUpStack\Annotations\InvalidArgumentException;
use ScaleUpStack\Annotations\Tests\Resources\TestCase;

/**
 * Abstract test case for annotations. Just define the class name, the tag plus valid and invalid arguments.
 *
 * Cf. ScaleUpStack\Annotations\Annotation\VarAnnotationTest for an example.
 */
abstract class AbstractAnnotationTestCase extends TestCase
{
    protected $annotationClassName = '';

    protected $tag = '';

    protected $validArguments = [];

    protected $invalidArguments = [];

    public function data_provider_of_valid_arguments()
    {
        return $this->createProvidedData($this->validArguments);
    }

    /**
     * @test
     * @dataProvider data_provider_of_valid_arguments
     * @covers ::__construct()
     * @covers ::validateTag()
     */
    public function it_can_be_created_with_a_valid_arguments_string(string $argumentString)
    {
        // given a valid tag as defined in the class property
        // and a valid arguments string as provided by the parameter

        // when creating the annotation class
        /** @var AnnotationInterface $annotation */
        $annotation = new $this->annotationClassName($this->tag, $argumentString);

        // then the tag, and the arguments can be retrieved
        $this->assertSame($this->tag, $annotation->tag());
        $this->assertSame($argumentString, $annotation->arguments());

    }

    public function data_provider_of_invalid_arguments()
    {
        return $this->createProvidedData($this->invalidArguments);
    }

    /**
     * @test
     * @dataProvider data_provider_of_invalid_arguments
     */
    public function it_throws_an_exception_when_created_with_an_invalid_arguments_string(string $argumentString)
    {
        // given a valid tag as defined in the class property
        // and an invalid arguments string as provided by the parameter

        // when creating the annotation class
        // then an exception is thrown
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Invalid @%s type declaration "%s".',
                $this->tag,
                $argumentString
            )
        );

        new $this->annotationClassName($this->tag, $argumentString);
    }

    private function createProvidedData(array $arguments)
    {
        $dataProvider = [];
        foreach ($arguments as $argument) {
            $dataProvider[] = is_array($argument) ? $argument: [$argument];
        }
        return $dataProvider;
    }

    /**
     * @test
     * @covers ::validateTag()
     */
    public function it_cannot_be_created_with_an_invalid_tag()
    {
        // given an invalid tag
        $invalidTagName = 'invalid-tag';

        // when creating the annotion
        // then an exception is thrown
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The tag of the annotation must be "%s", but "%s" given.',
                $this->tag,
                $invalidTagName
            )
        );

        new $this->annotationClassName($invalidTagName, '');
    }
}
