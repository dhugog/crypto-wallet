<?php

namespace App\Notifications;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    private Transaction $transaction;
    private string $subject;
    private string $text;

    public function __construct(Transaction $transaction, string $subject, string $text)
    {
        $this->transaction = $transaction;
        $this->subject = $subject;
        $this->text = $text;
    }

    /**
     * Get the notification’s delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via()
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $currentDate = Carbon::now()->format('d/m/Y à\s H:i');

        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Olá, ' . explode(" ", $notifiable->name)[0] . '!')
            ->line($this->text)
            ->line($currentDate);
    }

    public function toDatabase()
    {
        return [
            'transaction_id' => $this->transaction->id
        ];
    }
}
