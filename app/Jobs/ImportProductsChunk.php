<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class ImportProductsChunk implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $from, public int $to)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {



        if ($this->from > 100) {

            $this->batch()->cancel();

        }

        $path = storage_path("app/products.csv");
        $file = fopen($path, 'r');
        fgetcsv($file);
        $currentRow = 1;
        while (($row = fgetcsv($file)) !== false) {

            if ($currentRow >= $this->from && $currentRow <= $this->to) {
                Product::create([
                        'sku' => $row[0],
                        'name' => $row[1],
                        'category' => $row[2],
                        'price' => $row[3],
                        'stock' => $row[4]
                    ]
                );
            }
            if ($currentRow > $this->to) {
                break;
            }

            $currentRow++;

        }
        fclose($file);


    }


    public function middleware(): array
    {
        return [new SkipIfBatchCancelled];
    }
}
