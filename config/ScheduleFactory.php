<?php

declare(strict_types=1);

namespace Config;

use App\Backup\EnqueueRunBackupsByNameCommand;
use BuzzingPixel\Scheduler\Frequency;
use BuzzingPixel\Scheduler\ScheduleItem;
use BuzzingPixel\Scheduler\ScheduleItemCollection;

readonly class ScheduleFactory implements \BuzzingPixel\Scheduler\ScheduleFactory
{
    public function createSchedule(): ScheduleItemCollection
    {
        return new ScheduleItemCollection(scheduleItems: [
            new ScheduleItem(
                runEvery: Frequency::DAY_AT_MIDNIGHT,
                class: EnqueueRunBackupsByNameCommand::class,
            ),
        ]);
    }
}
