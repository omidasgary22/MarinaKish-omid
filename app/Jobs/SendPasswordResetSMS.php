<?php

namespace App\Jobs;

use Error;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IPPanel\Client;
use IPPanel\Errors\HttpException;
use IPPanel\Errors\ResponseCodes;

class SendPasswordResetSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone_number;
    protected $code;

    /**
     * Create a new job instance.
     */
    public function __construct($phone_number, $code)
    {
        $this->phone_number = $phone_number;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiKey = "fX1rhTbFVoytfSoSHQ7j_ZmnqC4LpC1Mu6ELiDrruOg=";
        $client = new Client($apiKey);

        $pattern = [
            "code" => $this->code
        ];

        try {
            $messageId = $client->sendPattern(
                "7ph3sn6dg10h70f",
                "+983000505",
                $this->phone_number,
                $pattern
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
