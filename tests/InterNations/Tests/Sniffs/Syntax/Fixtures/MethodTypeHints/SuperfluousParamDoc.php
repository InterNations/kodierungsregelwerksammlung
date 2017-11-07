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

    /**
     * @param $var1 This is a description
     * @param $var2
     */
    public function something($var1, $var2): void
    {}
}
