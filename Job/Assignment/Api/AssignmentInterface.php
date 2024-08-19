<?php

namespace Job\Assignment\Api;

interface AssignmentInterface
{
    /**
     * Calculate date difference.
     *
     * @param string $startDateTime
     * @param string $endDateTime
     * @param string $type
     * @param string $conversionUnit
     * @param string $timeZone
     * @return array
     */
    public function calculate($startDateTime, $endDateTime, $type, $conversionUnit = null, $timeZone = null);
}
