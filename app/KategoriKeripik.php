<?php

namespace App;

enum KategoriKeripik: string
{
    case Buah = 'Keripik Buah';
    case TempeDanGurih = 'Keripik Tempe & Gurih';
    case BundlingDanHampers = 'Paket Bundling & Hampers';

    public function label(): string
    {
        return $this->value;
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_map(
            static fn (self $category): string => $category->value,
            self::cases(),
        );
    }
}
