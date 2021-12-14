<?php
declare(strict_types=1);

namespace App\Controller;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\Request\CurrencyPairGraphRequest;
use App\DTO\Currency\Response\CurrencyPairGraphResponse;
use App\Repository\CurrencyRate\CurrencyRateAggregateRepositoryInterface;
use App\Repository\CurrencyRate\Exception\CurrencyRateAggregateRepositoryException;
use App\Settings\DateTimeSettings;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GraphController
{
    private CurrencyRateAggregateRepositoryInterface $currencyRateRepository;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private ValidatorInterface $validator;

    public function __construct(
        DenormalizerInterface $denormalizer,
        NormalizerInterface $normalizer,
        CurrencyRateAggregateRepositoryInterface $currencyRateRepository,
        ValidatorInterface $validator
    ) {
        $this->denormalizer = $denormalizer;
        $this->normalizer = $normalizer;
        $this->currencyRateRepository = $currencyRateRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/graphs", name="graphs")
     */
    public function getGraphs(Request $request, DateTimeSettings $dateTimeSettings)
    {
        /** @var CurrencyPairGraphRequest $requestDto */
        $requestDto = $this->denormalizer->denormalize($request->query->all(), CurrencyPairGraphRequest::class);
        $errors = $this->validator->validate($requestDto);
        if (count($errors) > 0) {
            $errors = $this->normalizer->normalize($errors);
            return new JsonResponse($errors['violations'], 422);
        }

        $dateTimeFormat = $dateTimeSettings->getDateTimeFormat();
        $result = [];
        foreach ($requestDto->getCurrencyPairs() as $pair) {
            $from = \DateTimeImmutable::createFromFormat($dateTimeFormat, $requestDto->getFrom());
            $to = $requestDto->getTo() ? \DateTimeImmutable::createFromFormat($dateTimeFormat, $requestDto->getTo()) : null;

            try {
                $collection = $this->currencyRateRepository->getAllByDateTimeRangeWithStep(
                    new CurrencyPair($pair->getBase(), $pair->getQuote()),
                    $from,
                    $to,
                    intval($requestDto->getStep())
                );
            } catch (CurrencyRateAggregateRepositoryException $e) {
                throw new HttpException(500, $e->getMessage());
            }

            $result[] = CurrencyPairGraphResponse::createFromRateCollectionDTO($collection, $dateTimeFormat);
        }

        return new JsonResponse($this->normalizer->normalize($result));
    }
}