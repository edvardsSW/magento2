<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Framework\Code\Validator;

use Magento\Framework\Code\ValidatorInterface;

class ConstructorArgumentTypes implements ValidatorInterface
{
    /**
     * @var \Magento\Framework\Code\Reader\ArgumentsReader
     */
    protected $argumentsReader;

    /**
     * @var \Magento\Framework\Code\Reader\SourceArgumentsReader
     */
    protected $sourceArgumentsReader;

    /**
     * @param \Magento\Framework\Code\Reader\ArgumentsReader $argumentsReader
     * @param \Magento\Framework\Code\Reader\SourceArgumentsReader $sourceArgumentsReader
     */
    public function __construct(
        \Magento\Framework\Code\Reader\ArgumentsReader $argumentsReader = null,
        \Magento\Framework\Code\Reader\SourceArgumentsReader $sourceArgumentsReader = null
    ) {
        $this->argumentsReader = $argumentsReader ?: new \Magento\Framework\Code\Reader\ArgumentsReader();
        $this->sourceArgumentsReader =
            $sourceArgumentsReader ?: new \Magento\Framework\Code\Reader\SourceArgumentsReader();
    }

    /**
     * Validate class constructor arguments
     *
     * @param string $className
     * @return bool
     * @throws \Magento\Framework\Code\ValidationException
     */
    public function validate($className)
    {
        $class = new \ReflectionClass($className);
        $expectedArguments = $this->argumentsReader->getConstructorArguments($class);
        $actualArguments = $this->sourceArgumentsReader->getConstructorArgumentTypes($class);
        $expectedArguments = array_column($expectedArguments, 'type');
        if (!empty(array_diff($expectedArguments, $actualArguments))) {
            throw new \Magento\Framework\Code\ValidationException(
                'Invalid constructor argument(s) in ' . $className
            );
        }
        return true;
    }
}
