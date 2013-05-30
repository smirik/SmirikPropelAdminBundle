<?php

namespace Smirik\PropelAdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FileToTextTransformer implements DataTransformerInterface
{
	/**
	 * Transforms an File to a string.
	 *
	 * @param null
	 * @return string
	 */
	public function transform($file)
	{
	    return null;
	}

	/**
	 * Returns file if presented.
	 *
	 * @param  string $text
	 * @return string
	 * @throws TransformationFailedException if object is not found.
	 */
	public function reverseTransform($file)
	{
        if (is_object($file))
        {
            return $file;
        }
        return null;
	}
}