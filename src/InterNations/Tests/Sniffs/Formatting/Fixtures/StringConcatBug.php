<?php

class BugFix
{
    public function test()
    {
        $suggestedEvent->setCoverImage(
            Photo::userUploadedPhoto($suggester, '/' . $suggestedEvent->getTitle() . '.jpg')
        );
    }
}
