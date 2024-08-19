<?php

namespace Job\Assignment\Model\Api;

use DateTime;
use DateTimeZone;
use Exception;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Framework\Escaper;
use Job\Assignment\Api\AssignmentInterface;

class Assignment implements AssignmentInterface
{
    /**
    * @var Escaper
    */
    private $escaper;

    public function __construct(Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

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
    public function calculate($startDateTime, $endDateTime, $type, $conversionUnit = null, $timeZone = null)
    {
        $response = [];

        try {
            // Sanitize inputs
            $startDateTime = $this->escaper->escapeHtml($startDateTime);
            $endDateTime = $this->escaper->escapeHtml($endDateTime);
            $conversionUnit = $this->escaper->escapeHtml($conversionUnit);
            $type = $this->escaper->escapeHtml($type);
            $timeZone = $this->escaper->escapeHtml($timeZone);

            // Validate date formats
            if (!$this->validateDate($startDateTime) || !$this->validateDate($endDateTime)) {
                throw new WebapiException(__('Invalid date format. Use YYYY-MM-DDTHH:MM:SS.'), 0, WebapiException::HTTP_BAD_REQUEST);
            }

            // Validate timezone
            if ($timeZone) {
                if (!in_array($timeZone, timezone_identifiers_list())) {
                    throw new WebapiException(__('Invalid timezone specified.'), 0, WebapiException::HTTP_BAD_REQUEST);
                }
            }

            // Validate type
            $validTypes = ['days', 'weekdays', 'weeks'];
            if (!in_array($type, $validTypes)) {
                throw new WebapiException(__('Invalid type specified.'), 0, WebapiException::HTTP_BAD_REQUEST);
            }

            // Validate unit if unit exists
            if ($conversionUnit) {
                $validUnits = ['seconds', 'minutes', 'hours', 'years'];
                if (!in_array($conversionUnit, $validUnits)) {
                    throw new WebapiException(__('Invalid unit specified.'), 0, WebapiException::HTTP_BAD_REQUEST);
                }
            }

            // If the time zone is specified otherwise taking UTC as default timezone
            $start = new DateTime($startDateTime, new DateTimeZone($timeZone ?: 'UTC'));
            $end = new DateTime($endDateTime, new DateTimeZone($timeZone ?: 'UTC'));

            $interval = $start->diff($end);

            switch ($type) {
                case 'days':
                    $result = $interval->days;
                    break;
                case 'weekdays':
                    $result = $this->calculateWeekdays($start, $end);
                    break;
                case 'weeks':
                    $result = floor($interval->days / 7);
                    break;
                default:
                    throw new WebapiException(__('Invalid type specified.'), 0, WebapiException::HTTP_BAD_REQUEST);
            }

            $responseItem = [
                'success' => true,
                'type' => $type,
                'result' => $result,
            ];

        if ($conversionUnit) {
            $start = new DateTime($startDateTime, new DateTimeZone($timeZone ?: 'UTC'));
            $end = new DateTime($endDateTime, new DateTimeZone($timeZone ?: 'UTC'));
            $convertedResult = $this->convertToUnit($result, $conversionUnit, $type, $start, $end);
            $responseItem['conversionUnit'] = $conversionUnit;
            $responseItem['convertedResult'] = $convertedResult;
        }

        $response[0] = $responseItem;
        } catch (Exception $e) {
            $response[0] = [
                'success' => false,
                'result' => __($e->getMessage())
            ];
        }
        return $response;
    }

    /**
    * Validate date format.
    *
    * @param string $date 
    * @return bool 
    */
    private function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);
        return $d && $d->format('Y-m-d\TH:i:s') === $date;
    }

    /**
    * Calculate the number of weekdays between two DateTime objects.
    *
    * @param DateTime $start
    * @param DateTime $end 
    * @return int Number of weekdays
    */
    private function calculateWeekdays(DateTime $start, DateTime $end)
    {
        $weekdayCount = 0;
        while ($start <= $end) {
            if ($start->format('N') < 6) {
                $weekdayCount++;
            }
                $start->modify('+1 day');
        }
        return $weekdayCount;
    }

    /**
    * Convert the result to the specified unit.
    *
    * @param int $value 
    * @param string $conversionUnit 
    * @param string $type
    * @return int 
    */
    private function convertToUnit($value, $conversionUnit, $type)
    {
        if ($type == 'weeks') {
            $value = $value * 7; // Convert weeks to days
        }   
        switch ($conversionUnit) {
            case 'seconds':
                return $value * 86400;
            case 'minutes':
                return $value * 1440;
            case 'hours':
                return $value * 24;
            case 'years':
                return $value / 365;
            default:
                return $value;
        }
    }
}
