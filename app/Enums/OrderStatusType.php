<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatusType extends Enum
{
    const Pending = 0;
    const In_Progress = 1;
    const Cancelled = 2;
    const Delayed = 3;
    const Shipped = 4;
    const Out_For_Delivery = 5;
    const Delivered = 6;
    const Returned_To_Sender = 7;

}
