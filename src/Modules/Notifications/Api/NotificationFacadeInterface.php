<?php

declare(strict_types=1);

namespace Modules\Notifications\Api;

use Modules\Notifications\Api\Dtos\NotifyData;

interface NotificationFacadeInterface
{
    public function notify(NotifyData $data): void;
}
