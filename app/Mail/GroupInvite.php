<?php

namespace App\Mail;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $group, $inviter, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->group = $invitation->group;
        $this->inviter = $invitation->group->owner;
        $this->user = $invitation->user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->user) {
            return $this->markdown('mails.group_invites.existing_user');
        }
        return $this->markdown('mails.group_invites.new_user');
    }
}
