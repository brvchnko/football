<?php

declare(strict_types=1);

namespace App\Tests\ParamConverters;

use App\ParamConverters\InputModelParamConverter;
use App\Tests\ParamConverters\Model\DemoModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InputModelParamConverterTest extends TestCase
{
    public function testApply(): void
    {
        $content = '{"name": "demo name"}';

        $request = Request::create('', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);

        /** @var SerializerInterface&MockObject $serializer */
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')
            ->with($content, DemoModel::class, 'json', [])
            ->willReturn((new DemoModel())->setName('demo name'));

        $converter = new InputModelParamConverter(
            $serializer,
            $this->createMock(ValidatorInterface::class)
        );

        $configuration = new ParamConverter(
            [
                'name' => 'demo',
                'class' => DemoModel::class,
            ]
        );

        $converter->apply($request, $configuration);

        $this->assertTrue($request->attributes->has('demo'));

        /** @var DemoModel $model */
        $model = $request->attributes->get('demo');
        $this->assertInstanceOf(DemoModel::class, $model);
        $this->assertEquals('demo name', $model->getName());
    }

    public function testApplyAndValidate(): void
    {
        $content = '{"name": "demo name"}';

        $request = Request::create('', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);

        $inputModel = (new DemoModel())->setName('demo name');

        /** @var SerializerInterface&MockObject $serializer */
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')
            ->with($content, DemoModel::class, 'json', [])
            ->willReturn($inputModel);

        /** @var ValidatorInterface&MockObject $validator */
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')
            ->with($inputModel, null, ['Group1', 'Group2'])
            ->willReturn(new ConstraintViolationList());

        $converter = new InputModelParamConverter(
            $serializer,
            $validator
        );

        $configuration = new ParamConverter(
            [
                'name' => 'demo',
                'class' => DemoModel::class,
                'options' => [
                    'validate' => true,
                    'validation_groups' => ['Group1', 'Group2'],
                ],
            ]
        );

        $converter->apply($request, $configuration);

        $this->assertTrue($request->attributes->has('demo'));

        /** @var DemoModel $model */
        $model = $request->attributes->get('demo');
        $this->assertInstanceOf(DemoModel::class, $model);
        $this->assertEquals('demo name', $model->getName());
    }

    /**
     * @expectedException \App\Exceptions\EntityValidationException
     */
    public function testFailsValidation(): void
    {
        $content = '{"name": "demo name"}';

        $request = Request::create('', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);

        $inputModel = (new DemoModel())->setName('demo name');

        /** @var SerializerInterface&MockObject $serializer */
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')
            ->with($content, DemoModel::class, 'json', [])
            ->willReturn($inputModel);

        /** @var ConstraintViolationList&MockObject $constraintViolations */
        $constraintViolations = $this->createMock(ConstraintViolationList::class);
        $constraintViolations
            ->method('count')
            ->willReturn(1);

        /** @var ValidatorInterface&MockObject $validator */
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')
            ->with($inputModel, null, ['Default'])
            ->willReturn($constraintViolations);

        $converter = new InputModelParamConverter(
            $serializer,
            $validator
        );

        $configuration = new ParamConverter(
            [
                'name' => 'demo',
                'class' => DemoModel::class,
                'options' => [
                    'validate' => true,
                ],
            ]
        );

        $converter->apply($request, $configuration);
    }

    /**
     * @dataProvider configurationDataProvider
     *
     * @param ParamConverter $configuration
     * @param bool           $expected
     */
    public function testSupports(ParamConverter $configuration, bool $expected): void
    {
        $converter = new InputModelParamConverter(
            $this->createMock(SerializerInterface::class),
            $this->createMock(ValidatorInterface::class)
        );

        $this->assertEquals($expected, $converter->supports($configuration));
    }

    public function configurationDataProvider(): \Generator
    {
        yield [new ParamConverter([]), false];
        yield [new ParamConverter(['class' => DemoModel::class]), true];
    }
}
