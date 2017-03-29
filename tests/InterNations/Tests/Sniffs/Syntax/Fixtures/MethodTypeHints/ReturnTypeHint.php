<?php

class ReturnTypeHint

{
    public function testNoReturnTypeHint()
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

    public function testNoWrongStyleTypeHint() : Attendance
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


    public function testMissingDocForArrayReturnTypeHing(): array
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