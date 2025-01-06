<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Notification\Customer as CustomerNofify;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class AutoSMSNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:autoSMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automaticly notify customer about scheduled maintenance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $datetimeNow = Carbon::now();
        $datetimeNow = date('Y-m-d h:i:s');

        $orderProductItems = OrderProduct::whereNotNull('scheduled_notify_id')
            ->where([
                ['scheduled_notify_at', '<=', $datetimeNow],
                ['notification_sent', 0],
                ['trash', 0]
            ])
            ->get();

        // $this->info($datetimeNow);
        // $this->info($orderProductItems);

        $bar = $this->output->createProgressBar(count($orderProductItems));
        $bar->start();

        if (!empty($orderProductItems)) {
            foreach ($orderProductItems as $key => $value) {
                $order = isset($value->order) && !empty($value->order) ? $value->order : null;
                $customer = isset($value->order->customer) && !empty($value->order->customer) ? $value->order->customer : null;
                $productItem = $value;
                $notificationData = DB::table('notifications')->where('id', $value->scheduled_notify_id)->first();

                if (!empty($order) && $order->trash != 1 && !empty($customer) && !empty($productItem) && !empty($notificationData)) {
                    // sms send logic
                    CustomerNofify::scheduledSMS($order, $customer, $notificationData, $productItem);
                    info('Order No: "'.$order->no.'" scheduled sms sent.');
                    // sms send logic
                }

                $bar->advance();
            }
        }

        $bar->finish();
        $this->info(' Scheduled SMS sent');
        // return Command::SUCCESS;
    }
}
