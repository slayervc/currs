<?php
declare(strict_types=1);

namespace App\Controller;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\Request\CurrencyPairGraphRequest;
use App\DTO\Currency\Response\CurrencyPairGraphResponse;
use App\Repository\CurrencyRateRepositoryInterface;
use App\Settings\DateTimeSettings;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GraphController
{
    private CurrencyRateRepositoryInterface $currencyRateRepository;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;

    public function __construct(
        DenormalizerInterface $denormalizer,
        NormalizerInterface $normalizer,
        CurrencyRateRepositoryInterface $currencyRateRepository
    ) {
        $this->denormalizer = $denormalizer;
        $this->normalizer = $normalizer;
        $this->currencyRateRepository = $currencyRateRepository;
    }

    /**
     * @Route("/graphs", name="graphs")
     */
    public function getGraphs(Request $request, DateTimeSettings $dateTimeSettings)
    {
        /** @var CurrencyPairGraphRequest $requestDto */
        $requestDto = $this->denormalizer->denormalize($request->query->all(), CurrencyPairGraphRequest::class);
        $dateTimeFormat = $dateTimeSettings->getDateTimeFormat();
        $result = [];

        foreach ($requestDto->currencyPairs as $pair) {
            $from = \DateTimeImmutable::createFromFormat($dateTimeFormat, $requestDto->from);
            $to = \DateTimeImmutable::createFromFormat($dateTimeFormat, $requestDto->to);

            $collection = $this->currencyRateRepository->getAllByDateTimeRangeWithStep(
                new CurrencyPair($pair->base, $pair->quote),
                $from,
                $to,
                intval($requestDto->step)
            );

            $result[] = CurrencyPairGraphResponse::createFromRateCollectionDTO($collection, $dateTimeFormat);
        }

        return new JsonResponse($this->normalizer->normalize($result));
    }
}