<?php

namespace App\Jobs;

use Error;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IPPanel\Client;
use IPPanel\Errors\Error as IPPanelError;
use IPPanel\Errors\HttpException;
use IPPanel\Errors\ResponseCodes;

class SendVerificationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $code;

    /**
     * Create a new job instance.
     */
    public function __construct($phone_number, $code)
    {
        $this->mobile = $phone_number;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiKey = "fX1rhTbFVoytfSoSHQ7j_ZmnqC4LpC1Mu6ELiDrruOg=";  // کلید API خود را اینجا وارد کنید
        $client = new Client($apiKey);

        $patternValues = [
            "verification-code" => $this->code
        ];

        try {
            $messageId = $client->sendPattern(
                "r1s4qvamsxpoted",          // کد الگوی پیامک
                "+983000505",               // شماره فرستنده
                $this->mobile,              // شماره موبایل گیرنده
                $patternValues              // داده‌های الگو
            );
            echo "Message sent successfully. Message ID: " . $messageId;
        } catch (Error $e) {
            // Handle IPPanel errors
            echo "Error: " . $e->getMessage();
            if ($e->code() == ResponseCodes::ErrUnprocessableEntity) {
                echo "Unprocessable entity";
            }
        } catch (HttpException $e) {
            // Handle HTTP exceptions
            echo "HTTP Error: " . $e->getMessage();
        }
    }
}
