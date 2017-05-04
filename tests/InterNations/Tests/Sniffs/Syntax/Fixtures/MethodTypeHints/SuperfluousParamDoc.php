<?php

class superfluousParamDoc
{
    /**
     * @param Activity $activity
     * @param User $attendee
     */
    public function accept(Activity $activity, User $attendee): Attendance
    {
        return;
    }
}