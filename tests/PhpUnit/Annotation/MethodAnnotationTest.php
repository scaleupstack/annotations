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

use ScaleUpStack\Annotations\Annotation\MethodAnnotation;
use ScaleUpStack\Annotations\Tests\Resources\Annotation\AbstractAnnotationTestCase;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\Annotation\MethodAnnotation
 */
final class MethodAnnotationTest extends AbstractAnnotationTestCase
{
    protected $annotationClassName = MethodAnnotation::class;

    protected $tag = 'method';

    protected $validArguments = [
        [
            'foo()',
            'foo',  // method name
            [],     // parameters
            null,   // return type declaration
            false,  // is static?
        ],
        [
            'int foo()',
            'foo',
            [],
            'int',
            false,
        ],
        [
            'int foo(string $someString)',
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => false,
                ]
            ],
            'int',
            false,
        ],
        [
            'int foo(string $someString, \DateTime $date, $withoutDataType)',
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => false,
                ],
                'date' => [
                    'dataType' => '\DateTime',
                    'hasDefaultValue' => false,
                ],
                'withoutDataType' => [
                    'dataType' => null,
                    'hasDefaultValue' => false,
                ],
            ],
            'int',
            false,
        ],
        [
            'int foo(string $someString = "Default value")',
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => true,
                    'default' => '"Default value"',
                ],
            ],
            'int',
            false,
        ],
        [
            "int foo(string \$someString = 'Default value')",
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => true,
                    'default' => "'Default value'",
                ],
            ],
            'int',
            false,
        ],
        [
            'null foo(string $someString = null)',
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => true,
                    'default' => 'null',
                ],
            ],
            'null',
            false,
        ],
        [
            'foo(string $someString = "default has , and ) in it ")',
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => true,
                    'default' => '"default has , and ) in it "',
                ],
            ],
            null,
            false,
        ],
        [
            'foo(string $someString = "default value with escaped \" double quote", $otherParam = "some value")',
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => true,
                    'default' => '"default value with escaped \" double quote"',
                ],
                'otherParam' => [
                    'dataType' => null,
                    'hasDefaultValue' => true,
                    'default' => '"some value"',
                ],
            ],
            null,
            false,
        ],
        [
            "foo(string \$someString = 'default value with escaped \' single quote', \$otherParam = 'some value')",
            'foo',
            [
                'someString' => [
                    'dataType' => 'string',
                    'hasDefaultValue' => true,
                    'default' => "'default value with escaped \' single quote'",
                ],
                'otherParam' => [
                    'dataType' => null,
                    'hasDefaultValue' => true,
                    'default' => "'some value'",
                ],
            ],
            null,
            false,
        ],
        [
            "static foo()",
            'foo',
            [],
            null,
            true,
        ],
        [
            "static int foo()",
            'foo',
            [],
            'int',
            true,
        ],
        [
            'int|null foo()',
            'foo',
            [],
            'int|null',
            false,
        ]
    ];

    protected $invalidArguments = [
        'fooWithoutBrackets',
        'foo(missingDollarSign)',
        'foo($missingClosingBracket',
    ];

    /**
     * Parent method overwritten because of coverage.
     *
     * @test
     * @dataProvider data_provider_of_invalid_arguments
     * @covers ::parseParametersString()
     */
    public function it_throws_an_exception_when_created_with_an_invalid_arguments_string(string $argumentString)
    {
        parent::it_throws_an_exception_when_created_with_an_invalid_arguments_string($argumentString);
    }

    /**
     * @test
     * @dataProvider data_provider_of_valid_arguments
     * @covers ::parseParametersString()
     * @covers ::methodName()
     * @covers ::parameters()
     * @covers ::returnType()
     * @covers ::isStatic()
     */
    public function it_extracts_method_name_parameters_and_return_type_from_arguments_string(
        string $arguments,
        string $expectedMethodName,
        array $expectedParameters,
        ?string $expectedReturnType,
        bool $isStatic
    )
    {
        // given an arguments string as provided via the data provider

        // when creating the property
        /** @var MethodAnnotation $annotation */
        $annotation = new MethodAnnotation('method', $arguments);

        // then method name, parameters and return type are extracted
        $this->assertSame($expectedMethodName, $annotation->methodName());
        $this->assertSame($expectedParameters, $annotation->parameters());
        $this->assertSame($expectedReturnType, $annotation->returnType());
        $this->assertSame($isStatic, $annotation->isStatic());
    }
}
