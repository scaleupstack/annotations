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

use ScaleUpStack\Annotations\DocBlockParser;

/**
 * @coversDefaultClass \ScaleUpStack\Annotations\DocBlockParser
 */
final class DocBlockParserTest extends TestCase
{
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
        $parser = new DocBlockParser($namespace);

        // then an instance is created
        $this->assertInstanceOf(DocBlockParser::class, $parser);
    }
}
