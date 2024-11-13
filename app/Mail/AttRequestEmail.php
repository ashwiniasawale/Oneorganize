<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;
class AttRequestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $employee_id;
    public $date;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee_id, $date)
    {
        $this->employee_id = $employee_id;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

     $emp_data=Employee::where('id',$this->employee_id)->first();
        return $this->markdown('email.attendance_request')
        ->subject('New Attendance Request')
        ->with([
            'employee_id' => $this->employee_id,
            'employee_name'=>$emp_data->name,
            'email'=>$emp_data->email,
            'date' => $this->date,
        ]);
      
    }
}
