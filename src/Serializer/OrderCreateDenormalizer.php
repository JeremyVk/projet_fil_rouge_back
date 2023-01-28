<?php
// api/src/Serializer/PlainIdentifierDenormalizer

namespace App\Serializer;

use App\Services\OrderService;
use ApiPlatform\Api\IriConverterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;

class OrderCreateDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function __construct(
        private IriConverterInterface $iriConverter,
        private OrderService $orderService,    
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $this->orderService->createOrder($data);

        return new JsonResponse('The product does not exist');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
       return $type === "App\Entity\Order";
    }
}