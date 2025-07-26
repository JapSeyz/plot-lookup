<?php

namespace Japseyz\PlotLookup\Commands;

use Illuminate\Console\Command;

class PlotLookupCommand extends Command
{
    public $signature = 'plot-lookup';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
