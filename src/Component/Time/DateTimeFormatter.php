<?php

namespace Umbrella\CoreBundle\Component\Time;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DateTimeFormatter
 */
class DateTimeFormatter
{
    private TranslatorInterface $translator;

    /**
     * Constructor
     *
     * @param TranslatorInterface $translator Translator used for messages
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns a formatted diff for the given from and to datetimes
     */
    public function formatDiff(\DateTimeInterface $from, \DateTimeInterface $to, ?string $locale = null): string
    {
        static $units = [
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        $diff = $to->diff($from);

        foreach ($units as $attribute => $unit) {
            $count = $diff->$attribute;
            if (0 !== $count) {
                return $this->doGetDiffMessage($count, (bool) $diff->invert, $unit, $locale);
            }
        }

        return $this->getEmptyDiffMessage($locale);
    }

    /**
     * Returns the diff message for the specified count and unit
     *
     * @param int    $count  The diff count
     * @param bool   $invert Whether to invert the count
     * @param string $unit   The unit must be either year, month, day, hour,
     *                       minute or second
     */
    public function getDiffMessage(int $count, bool $invert, string $unit, ?string $locale = null): string
    {
        if (0 === $count) {
            throw new \InvalidArgumentException('The count must not be null.');
        }

        $unit = strtolower($unit);

        if (!in_array($unit, ['year', 'month', 'day', 'hour', 'minute', 'second'])) {
            throw new \InvalidArgumentException(sprintf('The unit \'%s\' is not supported.', $unit));
        }

        return $this->doGetDiffMessage($count, $invert, $unit, $locale);
    }

    /**
     * Returns a DateTime instance for the given datetime
     */
    public function getDateTimeObject($dateTime = null): \DateTimeInterface
    {
        if ($dateTime instanceof \DateTimeInterface) {
            return $dateTime;
        }

        if (is_int($dateTime)) {
            $dateTime = date('Y-m-d H:i:s', $dateTime);
        }

        return new \DateTime($dateTime);
    }

    /**
     * Returns the message for an empty diff
     */
    public function getEmptyDiffMessage(?string $locale = null): string
    {
        return $this->translator->trans('diff.empty', [], 'time', $locale);
    }

    protected function doGetDiffMessage(int $count, bool $invert, string $unit, ?string $locale = null): string
    {
        $id = sprintf('diff.%s.%s', $invert ? 'ago' : 'in', $unit);

        return $this->translator->trans($id, ['%count%' => $count], 'time', $locale);
    }
}
