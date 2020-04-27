<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Réinitialisation de mot de passe')
                    ->from(config('mail.from.address'))
                    ->greeting(Lang::getFromJson('Mot de passe oublié | LmdSoft'))
                    ->line(Lang::getFromJson('Vous recevez ce email parce que nous avons reçu une demande de réinitialisation du mot de passe de votre compte.'))
                    ->action(
                        Lang::getFromJson('Réinitialiser le mot de passe'),
                        url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false))
                    )
                    ->line(Lang::getFromJson('Ce lien expirera dans :count minutes.', ['count' => config('auth.passwords.users.expire')]))
                    ->line(Lang::getFromJson("Si vous n'avez émis aucune demande de réinitialisation du mot de passe, veuillez ignorer cet email."));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
