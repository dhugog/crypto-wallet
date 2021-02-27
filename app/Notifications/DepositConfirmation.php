<?php

namespace App\Notifications;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
        $amount = number_format($this->transaction->credited_amount, 2, ',', '.');

        return (new MailMessage)
            ->subject('Depósito efetuado com sucesso!')
            ->greeting('Olá, ' . explode(" ", $notifiable->name)[0] . '!')
            ->line("Seu depósito no valor de **R$ {$amount}** foi efetuado com sucesso!")
            ->line($currentDate);
    }

    public function toDatabase()
    {
        return $this->transaction->toArray();
    }
}
