<?php



namespace App\AI\Stores;

use OskarStark\Enum\Trait\Comparable;

/**
 * @author Denis Zunke <denis.zunke@gmail.com>
 */
enum Distance: string
{
    use Comparable;

    case Cosine = 'cosine';
    case InnerProduct = 'inner_product';
    case L1 = 'l1';
    case L2 = 'l2';

    public function getComparisonSign(): string
    {
        return match ($this) {
            self::Cosine => '<=>',
            self::InnerProduct => '<#>',
            self::L1 => '<+>',
            self::L2 => '<->',
        };
    }
}
