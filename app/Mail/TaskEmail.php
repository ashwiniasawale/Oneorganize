<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        $content = "Please check attached file.";
       
        $storagePath=storage_path($this->filePath);
        return $this->subject('Task Export')
        ->attach($storagePath, [
            'as' => 'tasks_export.csv',
            'mime' => 'text/csv',
        ])
        ->markdown('email.task_mail', ['content' => $content]);
        
    }
}