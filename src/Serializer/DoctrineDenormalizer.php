<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{

    /** * @var EntityManagerInterface */
    private $entityManagerInterface;
    
    /**
    * Constructor
    */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }
    /**
     *
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     */
    public function supportsDenormalization($data, string $type, ?string $format = null): bool
    {
        $dataIsID = is_numeric($data);

        $typeIsEntity = (strpos($type, 'App\Entity') === 0);

        $typeIsNotArray = !(strpos($type, ']') === (strlen($type) -1));

        return $typeIsEntity && $dataIsID && $typeIsNotArray;
    }

    /**
     *
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * 
     * @return mixed
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $denormalizedEntity = $this->entityManagerInterface->find($type, $data);
        if ($denormalizedEntity === null)
        {
            throw new EntityNotFoundException($type . "#". $data ." not found");
        }
        return $denormalizedEntity;
    }
}