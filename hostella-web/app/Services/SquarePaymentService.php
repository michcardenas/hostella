<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Square\SquareClient;
use Square\Environments;
use Square\Types\Money;
use Square\Types\Currency;
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Types\CashPaymentDetails;
use Square\Exceptions\SquareApiException;

class SquarePaymentService
{
    protected SquareClient $client;

    public function __construct()
    {
        $this->client = new SquareClient(
            token: env('SQUARE_ACCESS_TOKEN'),
            options: [
                'baseUrl' => env('APP_ENV') === 'production'
                    ? Environments::Production->value
                    : Environments::Sandbox->value,
            ]
        );
    }

    public function client(): SquareClient
    {
        return $this->client;
    }

    public function listLocations(): array
    {
        try {
            return $this->client->locations->list();
        } catch (SquareApiException $e) {
            throw new Exception("Error al listar ubicaciones: " . $e->getMessage());
        }
    }

    public function createCardPayment(string $sourceId, int $amountCents, string $currency = 'USD', ?string $note = null)
    {
        try {
            $payment = $this->client->payments->create(
                new CreatePaymentRequest([
                    'idempotencyKey' => Str::uuid()->toString(),
                    'sourceId' => $sourceId,
                    'amountMoney' => new Money([
                        'amount' => $amountCents,
                        'currency' => Currency::tryFrom($currency)?->value ?? Currency::Usd->value,
                    ]),
                    'note' => $note,
                ])
            );

            return $payment;
        } catch (SquareApiException $e) {
            throw new Exception("Error al procesar el pago con tarjeta: " . $e->getMessage());
        }
    }

    public function createCashPayment(int $amountCents, int $cashGivenCents, string $currency = 'USD', ?string $note = null)
    {
        try {
            $payment = $this->client->payments->create(
                new CreatePaymentRequest([
                    'idempotencyKey' => Str::uuid()->toString(),
                    'sourceId' => 'CASH',
                    'amountMoney' => new Money([
                        'amount' => $amountCents,
                        'currency' => Currency::tryFrom($currency)?->value ?? Currency::Usd->value,
                    ]),
                    'cashDetails' => new CashPaymentDetails([
                        'buyerSuppliedMoney' => new Money([
                            'amount' => $cashGivenCents,
                            'currency' => Currency::tryFrom($currency)?->value ?? Currency::Usd->value,
                        ])
                    ]),
                    'note' => $note,
                ])
            );

            return $payment;
        } catch (SquareApiException $e) {
            throw new Exception("Error al procesar el pago en efectivo: " . $e->getMessage());
        }
    }

    public function createPayment(string $sourceId, int $amountCents, string $currency = 'USD', ?string $note = null)
    {
        $paymentRequest = new \Square\Payments\Requests\CreatePaymentRequest([
            'idempotencyKey' => \Illuminate\Support\Str::uuid()->toString(),
            'sourceId' => $sourceId,
            'amountMoney' => new \Square\Types\Money([
                'amount' => $amountCents,
                'currency' => $currency,
            ]),
            'note' => $note,
        ]);

        return $this->client->payments->create($paymentRequest);
    }
}
