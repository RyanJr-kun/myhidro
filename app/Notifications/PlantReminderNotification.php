<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tanaman; // Import model Tanaman

class PlantReminderNotification extends Notification // implements ShouldQueue // Opsional: untuk antrian
{
    use Queueable;

    protected $tanaman;
    protected $reminderType; // 'pupuk' atau 'air'

    /**
     * Create a new notification instance.
     */
    public function __construct(Tanaman $tanaman, string $reminderType)
    {
        $this->tanaman = $tanaman;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // Kirim via email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = '';
        $line = '';
        $actionText = 'Lihat Detail Tanaman';
        // $actionUrl = route('tanaman.show', $this->tanaman->id); // Perlu route show jika ada

        if ($this->reminderType === 'pupuk') {
            $subject = 'Pengingat Pemupukan Tanaman';
            $line = "Saatnya memberikan pupuk untuk tanaman {$this->tanaman->nama_tanaman}.";
        } elseif ($this->reminderType === 'air') {
            $subject = 'Pengingat Cek Air/Nutrisi';
            $line = "Saatnya memeriksa kondisi air/nutrisi untuk tanaman {$this->tanaman->nama_tanaman}.";
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting('Halo!')
                    ->line($line)
                    // ->action($actionText, $actionUrl) // Tombol di email (opsional)
                    ->line('Terima kasih telah menggunakan aplikasi MyHidro!');
    }

    /**
     * Get the array representation of the notification.
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Bisa digunakan jika Anda ingin menyimpan notif ke database juga
        ];
    }
}
