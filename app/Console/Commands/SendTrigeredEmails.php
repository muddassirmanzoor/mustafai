<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\TrigerEmail;

class SendTrigeredEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron is for sending emails.';

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
     * @return int
     */
    public function handle()
    {
        $emails = TrigerEmail::where('is_sent', 0)->get();

        foreach ($emails as $email) {
            $details = json_decode($email->details, true);

            $isSend = 1;
            try {
                \Mail::to($email->email)->send(new \App\Mail\CommonMail($details));
                $isSend = 1;
                \Log::info('Email Sent to ' . $email->email . ' id is ' . $email->id);
            } catch (Exception $e) {
                $isSend = 2;
                \Log::info('Email sending failed to ' . $email->email . ' id is ' . $email->id);
                
            }

            if ($isSend) {
                TrigerEmail::where('id', $email->id)->update(['is_sent' => 1]);
            }
        }
    }
}
