<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\Guest;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ImportBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bookingcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports booking csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = public_path('assets/bookings.csv');
        $delimiter = ',';
        if (!file_exists($filename) || !is_readable($filename)) {
            $this->error('No csv provided');
            return;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header) {
                    $header = $row;
                }
                else
                {
                    $data = array_combine($header, $row);
                   
                    $room = Room::firstOrCreate(['name' => $data['room']]);
                    $phoneNumber = env("PHONE_PREFIX") . $phone = preg_replace('/\D+/', '', $data['phonenumber']);
                  
                    $seconds = 24*60*60;
                    $guest = Cache::remember($phoneNumber, $seconds, function () use ($phoneNumber, $data) {
                        return Guest::firstOrCreate(['name' => $data['guestname'], 'phonenumber' => $phoneNumber, 'country' => $data['country']]);
                    });

                    $booking = Booking::firstOrNew(['room_id' => $room->id, 'checkin' => date('Y-m-d', strtotime($data['checkin']))]);
                    if($booking->id) {
                        $this->error('Booking overlap');
                        continue;
                    }
                    $booking->guest_id = $guest->id;
                    $booking->checkout = date('Y-m-d', strtotime($data['checkout']));
                    $booking->save();
                }  
            }
            fclose($handle);
        }

        $this->info('CSV imported successfully.');
    }
}
