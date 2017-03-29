<?php

class NoReturnTypeHint

{
    /**
     * @return array[] $attendance
     */
    public function __clone(): array
    {
        if ($this->accessResolver->resolve($activity->getGuestlist(), $attendee)->canAttendWithTrial()) {
            $attendance = $this->attendanceService->acceptUsingTrial($activity->getGuestlist(), $attendee);

            $this->dispatcher->dispatch(
                ActivityGroupEvents::onActivityAttendanceAccept,
                AttendanceEvent::createTrial($attendance, $activity)
            );

            return $attendance;
        }

        if ($this->needsToJoinGroup($activity->getActivityGroup(), $attendee)) {
            throw LogicException::mustJoinGroup();
        }

        $attendance = $this->attendanceService->accept($activity->getGuestlist(), $attendee);

        $this->dispatcher->dispatch(
            ActivityGroupEvents::onActivityAttendanceAccept,
            AttendanceEvent::create($attendance, $activity)
        );

        return $attendance;
    }
}