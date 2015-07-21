<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * The type for a error flash message.
     *
     * @var string
     */
    const FLASH_ERROR = 'error';

    /**
     * The type for a warning flash message.
     *
     * @var string
     */
    const FLASH_WARNING = 'warning';

    /**
     * The type for a success flash message.
     *
     * @var string
     */
    const FLASH_SUCCESS = 'success';

    /**
     * Flashes a message to the user.
     *
     * @param  string $message
     * @param  string $type
     * @return void
     */
    protected function flash($message, $type = self::FLASH_SUCCESS)
    {
        $messages = session('__flash_messages.' . $type, []);
        $messages[] = $message;

        session()->flash('__flash_messages.' . $type, $messages);
    }
}
