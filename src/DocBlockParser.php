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

namespace ScaleUpStack\Annotations;

final class DocBlockParser
{
    private $namespace;

    /**
     * @param string $namespace
     *        The namespace of the corresponding class/interface is used to resolve class or interface names in
     *        docblocks.
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function parse(string $docBlock = null) : Annotations
    {
        $annotations = new Annotations();

        if (is_null($docBlock)) {
            return $annotations;
        }

        return $annotations;
    }
}
