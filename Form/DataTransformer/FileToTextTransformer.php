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
	 * Transforms a string to an object.
	 *
	 * @param  string $text
	 * @return string
	 * @throws TransformationFailedException if object is not found.
	 */
	public function reverseTransform($text)
	{
        if (is_object($text))
        {
            return $text;
        }
        return null;
	}
}