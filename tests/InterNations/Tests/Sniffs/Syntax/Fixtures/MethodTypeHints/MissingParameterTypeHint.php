<?php

class MissingParameterTypeHint
{
    /**
     * @return Integer[]
     */
    public function postAction(Request $request, $context, $entityType): array
    {
        return;
    }
}