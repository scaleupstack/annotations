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

use ScaleUpStack\Annotations\Annotations;
use ScaleUpStack\Annotations\DocBlockParser;
use ScaleUpStack\Annotations\InvalidArgumentException;
use ScaleUpStack\Annotations\Tests\Resources\TestCase;

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
        $this->parser = new DocBlockParser();
    }

    public function data_provider_of_empty_docblocks()
    {
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
     * @covers ::extractTagsAndArguments()
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
            'some argument string for the first some-tag'
        );
        $expectedAnnotations->add(
            'some-tag',
            'some other argument string for the second some-tag'
        );
        $expectedAnnotations->add(
            'othertag',
            'some argument string for another tag'
        );

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    /**
     * @test
     */
    public function it_parses_docblocks_where_single_line_annotation_tag_has_no_argument_string()
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
        $expectedAnnotations->add('some-tag-without-argument-list', '');

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    /**
     * @test
     */
    public function it_removes_spaces_of_single_line_annotations()
    {
        // given a docblock with too many spaces at the beginning and at the end
        $docBlock = "/**\n" .
                    " * @unknown   some value   \n" .
                    " */";

        // when parsing the docblock
        $annotations = $this->parser->parse($docBlock);

        // then the arguments string is trimmed
        $expectedAnnotations = new Annotations();
        $expectedAnnotations->add('unknown', 'some value');

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    /**
     * @test
     * @covers ::extractTagsAndArguments()
     * @covers ::trim()
     */
    public function it_parses_multi_line_annotations_of_a_doc_block()
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
 *
 * Nothing to parse here
 *
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
            'single-line argument'
        );
        $expectedAnnotations->add(
            'some-tag',
            "multi-line\nvalue\n\nwith empty line"
        );
        $expectedAnnotations->add(
            'some-tag',
            "second\n  multi-line\nvalue"
        );
        $expectedAnnotations->add(
            'othertag',
            'argument in one line'
        );
        $expectedAnnotations->add(
            'othertag',
            'another argument in one line'
        );
        $expectedAnnotations->add(
            'othertag',
            'multi-line specification of one line'
        );

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    /**
     * @test
     */
    public function it_removes_trailing_spaces_of_multi_line_annotations()
    {
        // given a docblock with a multi-line annotation and trailing spaces
        $docBlock = "/**\n" .
                    " * @unknown {\n" .
                    " *     space at the end   \n" .
                    " * }\n" .
                    " */";

        // when parsing the docblock
        $annotations = $this->parser->parse($docBlock);

        // then the spaces at the end are trimmed
        $expectedAnnotations = new Annotations();
        $expectedAnnotations->add('unknown', 'space at the end');

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
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->parser->parse($invalidDocBlock);
    }

    /**
     * @test
     */
    public function it_ignores_an_at_sign_without_tag_name()
    {
        // given a DocBlock with @ sign but no tag name of the annotation
        $docBlock = <<<DocBlock
/**
 * @ there-was-a-space
 */
DocBlock;

        // when parsing the docblock
        $annotations = $this->parser->parse($docBlock);

        // then an empty Annotations collection is returned
        $this->assertEquals(new Annotations(), $annotations);
    }

    /**
     * @test
     * @covers ::extractTagsAndArguments()
     */
    public function it_throws_an_exception_on_invalid_multi_line_annotations()
    {
        // given an invalid multi-line specification
        $docBlock = <<<DocBlock
/**
 * @unknown {
 *    there is no closing "}"
 */
DocBlock;

        // when parsing the docblock
        // then an InvalidArgumentException is thrown
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Closing curly bracket in multi-line annotation is missing for @unknown.');

        $this->parser->parse($docBlock);
    }

    /**
     * @test
     */
    public function it_is_less_strict_about_spaces_before_the_star()
    {
        // given a docblock with irregular indention (or more than before a class block)
        $docBlock = <<<DocBlock
/**
      * @unknown {
 *   first line
         *    second line one space more after star
      * }
           */
DocBlock;

        // when parsing the docblock
        $annotations = $this->parser->parse($docBlock);

        // then the spacing of the option is relative to the stars
        $expectedAnnotations = new Annotations();
        $expectedAnnotations->add('unknown', "first line\n second line one space more after star");

        $this->assertEquals($expectedAnnotations, $annotations);
    }
}
