<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Controller\DataFlow;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Productsup\DataInspectionUtils\DTO\Api\Request\DataFlow\FieldUsageRequestDTO;
use Productsup\DataInspectionUtils\Strategy\DataFlow\Factory\FieldUsageInspectionStrategyFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route(
    path: '/api/data-flow/field',
    name: 'api.data_flow.field.',
)]
#[OA\Tag(
    name: 'Dataflow field inspections',
    description: 'Inspecting ',
)]
class DataFlowFieldInspectionController extends AbstractFOSRestController
{
    public function __construct(
        public readonly FieldUsageInspectionStrategyFactory $fieldUsageInspectionStrategyFactory
    ) {}

    #[Post(
        path: '/usage',
        name: 'usage'
    )]
    #[OA\Post(
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: FieldUsageRequestDTO::class)))
    )]
    #[ParamConverter('fieldUsageRequest', converter: 'fos_rest.request_body')]
    public function usage(FieldUsageRequestDTO $fieldUsageRequest): View
    {
        $fieldsUsageData = $this
            ->fieldUsageInspectionStrategyFactory->createFromFieldUsageFetchCommand($fieldUsageRequest)
            ->execute();

        return $this->view($fieldsUsageData);
    }
}