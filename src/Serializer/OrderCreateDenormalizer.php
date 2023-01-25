<?php
// api/src/Serializer/PlainIdentifierDenormalizer

namespace App\Serializer;

use ReflectionClass;
use App\Entity\Dummy;
use App\Entity\Order;
use App\Entity\BookVariant;
use App\Entity\RelatedDummy;
use App\Services\OrderService;
use ApiPlatform\Api\IriConverterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

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