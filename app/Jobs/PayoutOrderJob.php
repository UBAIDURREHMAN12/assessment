<?php

use App\Exceptions\PayoutException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

public function handle(ApiService $apiService)
{
    // Start a database transaction
    DB::beginTransaction();

    try {
        // Use the API service to send a payout of the correct amount
        $payoutResult = $apiService->sendPayout($this->order);

        // If the payout is successful, update the order status to 'paid'
        if ($payoutResult === true) {
            $this->order->update(['status' => 'paid']);
            Log::info('Payout successful for order: ' . $this->order->id);
        }

        // Commit the transaction
        DB::commit();
    } catch (\Exception $e) {
        // Rollback the transaction in case of exception
        DB::rollback();

        // Handle exceptions (e.g., API errors) when processing payouts
        Log::error('Error processing payout for order ' . $this->order->id . ': ' . $e->getMessage());
        throw new PayoutException('Failed to process payout: ' . $e->getMessage());
    }
}
}
