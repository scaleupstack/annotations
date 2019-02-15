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

namespace ScaleUpStack\Annotations\Tests;

use ScaleUpStack\Annotations\Annotations;
use ScaleUpStack\Annotations\DocBlockParser;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\DocBlockParser
 */
final class DocBlockParserTest extends TestCase
{
    /**
     * @var DocBlockParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new DocBlockParser(DocBlockParser::class);
    }

    /**
     * This test is mainly for code coverage.
     *
     * @test
     * @covers ::__construct()
     */
    public function it_can_be_created_with_the_namespace_of_the_class_to_parse()
    {
        // given a namespace of the class that will be parsed by this instance
        $namespace = DocBlockParser::class;

        // when creating the DocBlockParser
        $myParser = new DocBlockParser($namespace);

        // then an instance is created
        $this->assertInstanceOf(DocBlockParser::class, $myParser);
    }

    public function data_provider_of_empty_docblocks()
    {
        $nullDocBlock = null;
        $emptyStringDocBlock = '';
        $docBlockWithoutLines = <<<DocBlock
/**
 */
DocBlock;
        $docBlockWithText = <<<DocBlock
/**
 * Some description plus empty line.
 *
 */
DocBlock;

        return [
            [$nullDocBlock],
            [$emptyStringDocBlock],
            [$docBlockWithoutLines],
            [$docBlockWithText],
        ];
    }

    /**
     * @test
     * @dataProvider data_provider_of_empty_docblocks
     * @covers ::parse()
     * @covers ::stripDocBlock()
     */
    public function it_parses_a_docblock_without_annotations($emptyDocBlock)
    {
        // given an empty docblock as provided by the data provider

        // when parsing the docblock
        $annotations = $this->parser->parse($emptyDocBlock);

        // then an empty Annotations collection is returned
        $this->assertEquals(
            new Annotations(),
            $annotations
        );
    }

    /**
     * @test
     * @covers ::parse()
     * @covers ::stripDocBlock()
     * @covers ::extractTagsAndArguments()
     */
    public function it_parses_single_line_annotations_of_a_doc_block()
    {
        // given a docblock with two annotations: one has one occurence and the other one two
        $docBlock = <<<DocBlock
/**
 * Some irrelevant description
 *
 * @some-tag some argument string for the first some-tag
 * @some-tag some other argument string for the second some-tag
 * @othertag some argument string for another tag
 */
DocBlock;

        // when parsing the docblock
        $annotations = $this->parser->parse($docBlock);

        // then the annotations are created
        $expectedAnnotations = new Annotations();
        $expectedAnnotations->add(
            'some-tag',
            'some argument string for the first some-tag',
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'some-tag',
            'some other argument string for the second some-tag',
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'othertag',
            'some argument string for another tag',
            DocBlockParser::class
        );

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    /**
     * @test
     */
    public function it_parses_docblocks_where_annotation_tag_has_no_argument_string()
    {
        // given a docblock with an annotation withouth arguments string
        $docBlock = <<<DocBlock
/**
 * @some-tag-without-argument-list
 */
DocBlock;

        // when parsing the annotations
        $annotations = $this->parser->parse($docBlock);

        // then the annotation is created
        $expectedAnnotations = new Annotations();
        $expectedAnnotations->add('some-tag-without-argument-list', '', DocBlockParser::class);

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    /**
     * @test
     * @covers ::extractTagsAndArguments()
     * @covers ::trimLeft()
     */
    public function it_parses_multi_line_annoations_of_a_doc_block()
    {
        // given a docblock with single- and multi-line annotations
        $docBlock = <<<DocBlock
/**
 * Some irrelevant description
 *
 * @some-tag single-line argument
 * @some-tag {
 *     multi-line
 *     value
 *
 *     with empty line
 * }
 * @some-tag {
 *     second
 *       multi-line
 *     value
 * }
 * @othertag argument in one line
 * @othertag another argument in one line
 * @othertag {
 *   multi-line specification of one line
 * }
 */
DocBlock;

        // when parsing the docblock
        $annotations = $this->parser->parse($docBlock);

        // then the annotations are created
        $expectedAnnotations = new Annotations();
        $expectedAnnotations->add(
            'some-tag',
            'single-line argument',
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'some-tag',
            "multi-line\nvalue\n\nwith empty line",
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'some-tag',
            "second\n  multi-line\nvalue",
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'othertag',
            'argument in one line',
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'othertag',
            'another argument in one line',
            DocBlockParser::class
        );
        $expectedAnnotations->add(
            'othertag',
            'multi-line specification of one line',
            DocBlockParser::class
        );

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    public function data_provider_of_invalid_doc_blocks()
    {
        return [
            [
                '/*', // second '*' is missing
                'First line of DocBlock must be "/**" but "/*" given.',
            ],
            [
                '/** ', // additional space
                'First line of DocBlock must be "/**" but "/** " given.',
            ],
            [
                "/**\n" .
                " *//", // additional slash
                'Last line of DocBlock must be " */" but " *//" given.'
            ],
            [
                "/**\n" .
                " -\n" . // '-' instead of '*'
                " */",
                'Lines in a DocBlock must start with " * " or equal to " *", but " -" given.'
            ],
            [
                "/**\n" .
                "  \n" . // '*' missing
                " */",
                'Lines in a DocBlock must start with " * " or equal to " *", but "  " given.'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider data_provider_of_invalid_doc_blocks
     */
    public function it_throws_an_exeption_on_invalid_doc_block_formatting(
        string $invalidDocBlock,
        string $expectedExceptionMessage
    )
    {
        // given an invalid docblock as provided as parameter

        // when parsing the docblock
        // then an InvalidArgumentException is thrown
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->parser->parse($invalidDocBlock);
    }
}
