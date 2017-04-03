<?php

class MissingParameterTypeHint
{
    /**
     * @return int[]
     */
    public function postAction(Request $request, $context, $entityType): array
    {
        return;
    }
}