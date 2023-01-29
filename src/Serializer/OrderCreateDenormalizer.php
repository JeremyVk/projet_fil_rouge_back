<?php
// api/src/Serializer/PlainIdentifierDenormalizer

namespace App\Serializer;

use App\Services\Order\OrderService;
use ApiPlatform\Api\IriConverterInterface;
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
        return $this->orderService->createOrder($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
       return $type === "App\Entity\Order";
    }
}