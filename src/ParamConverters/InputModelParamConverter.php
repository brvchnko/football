<?php

declare(strict_types=1);

namespace App\ParamConverters;

use App\Exceptions\EntityValidationException;
use App\Model\Request\InputModelInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class InputModelParamConverter.
 */
final class InputModelParamConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $class = $configuration->getClass();
        $options = $this->getOptions($configuration);

        $object = $this->serializer->deserialize(
            $request->getContent(),
            $class,
            $request->getContentType()
        );

        if ($options['validate']) {
            $violations = $this->validator->validate($object, null, $options['validation_groups']);

            if ($violations->count() > 0) {
                throw new EntityValidationException($violations);
            }
        }

        $request->attributes->set($name, $object);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return \in_array(InputModelInterface::class, class_implements($configuration->getClass()), true);
    }

    private function getOptions(ParamConverter $configuration): array
    {
        return array_replace(
            [
                'validate' => false,
                'validation_groups' => ['Default'],
            ],
            $configuration->getOptions()
        );
    }
}
