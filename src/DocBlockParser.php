<?php declare(strict_types = 1);

/**
 * This file is part of ScaleUpStack/Annotations.
 *
 * For the full copyright and license information, please view the README.md and LICENSE.md files that were distributed
 * with this source code.
 *
 * @copyright 2019 - present ScaleUpVentures GmbH, https://www.scaleupventures.com
 * @link      https://github.com/scaleupstack/annotations
 */

namespace ScaleUpStack\Annotations;

final class DocBlockParser
{
    public function parse(string $docBlock = null) : Annotations
    {
        $collection = new Annotations();

        if (is_null($docBlock)) {
            return $collection;
        }

        $strippedLines = $this->stripDocBlock($docBlock);
        $annotationsData = $this->extractTagsAndArguments($strippedLines);

        foreach ($annotationsData as $data) {
            $collection->add(
                $data['tag'],
                $data['arguments']
            );
        }

        return $collection;
    }

    /**
     * Strippes all the DocBlock formatting and returns an array with the lines.
     */
    private function stripDocBlock(string $docBlock) : array
    {
        if ('' == $docBlock) {
            return [];
        }

        $lines = explode("\n", $docBlock);

        // validate first line
        $firstLine = array_shift($lines);
        Assert::same(
            '/**',
            $firstLine,
            'First line of DocBlock must be "/**" but %2$s given.'
        );

        // validate last line
        $lastLine = array_pop($lines);
        Assert::regex(
            $lastLine,
            '(^[ ]+\*/$)',
            'Last line of DocBlock must be " */" but %1$s given.'
        );

        // validate other lines
        Assert::allRegex(
            $lines,
            '/^[ ]+\*( |$)/',
            'Lines in a DocBlock must start with " * " or equal to " *", but %s given.'
        );

        // remove leading ' * ' or ' *' and return
        return preg_replace(
            '/^[ ]+\* ?/',
            '',
            $lines
        );
    }

    /**
     * Parses the lines and combines multi-line arguments
     */
    private function extractTagsAndArguments(array $lines) : array
    {
        if ([] === $lines) {
            return [];
        }

        $data = [];
        $tag = null;

        $stateSearchStartOfTag = 1;
        $stateSearchEndOfMultiLineValue = 2;

        $currentState = $stateSearchStartOfTag;

        foreach ($lines as $line) {
            if ($currentState === $stateSearchStartOfTag) {
                // pattern: ^@<name-of-tag><optional: space plus rest of line>
                $pattern = '(^@([a-z-]+)( (.*))?$)';
                $count = preg_match($pattern, $line, $matches);
                if (1 !== $count) {
                    continue;
                }

                // line with starting tag
                $tag = $matches[1];
                $restOfLine = '';
                if (array_key_exists(3, $matches)) {
                    $restOfLine = trim($matches[3], ' ');
                }

                if ('{' !== $restOfLine) {
                    // single-line arguments string
                    $data[] = [
                        'tag' => $tag,
                        'arguments' => $restOfLine,
                    ];
                } else {
                    // start of a multi-line arguments string
                    $currentState = $stateSearchEndOfMultiLineValue;
                    $arguments = [];
                }
            } else if ($currentState === $stateSearchEndOfMultiLineValue) {
                if ('}' !== $line) {
                    // additional line in multi-line argument string
                    $arguments[] = $line;
                } else {
                    // end of a multi-line argument string
                    $data[] = [
                        'tag' => $tag,
                        'arguments' => $this->trim($arguments),
                    ];
                    $currentState = $stateSearchStartOfTag;
                }
            }
        }

        if (
            ! is_null($tag) &&
            $currentState !== $stateSearchStartOfTag
        ) {
            throw new InvalidArgumentException(
                sprintf('Closing curly bracket in multi-line annotation is missing for @%s.', $tag)
            );
        }

        return $data;
    }

    /**
     * Alligns the lines on the left so that at least in one line there are no preceding spaces, and removes trailing
     * spaces on the right.
     */
    private function trim(array $lines) : string
    {
        // find shortest prefix of spaces
        $shortestSpacePrefix = null;

        $pattern = '(^([ ]*)[^ ])';
        foreach ($lines as $line) {
            $count = preg_match($pattern, $line, $matches);

            if (1 === $count) {
                $currentSpacePrefix = strlen($matches[1]);

                if (
                    is_null($shortestSpacePrefix) ||
                    $shortestSpacePrefix > $currentSpacePrefix
                ) {
                    $shortestSpacePrefix = $currentSpacePrefix;
                }
            }
        }

        // remove prefix in all lines
        $replacePattern = sprintf('/^([ ]{%d})/', $shortestSpacePrefix);
        $lines = preg_replace(
            $replacePattern,
            '',
            $lines
        );

        $lines = preg_replace(
            '/([ ]+)$/',
            '',
            $lines
        );

        return implode("\n", $lines);
    }
}
