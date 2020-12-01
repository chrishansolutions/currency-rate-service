<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mtownsend\XmlToArray\XmlToArray;
use App\Models\ImportCurrencyRate;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;


class importCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importCurrencyRates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used for import currency rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            //read entire currency rates xml file into string
            $xml = file_get_contents(config('global.currency_rates_xml_url'));
            $currencyRates = XmlToArray::convert($xml);
            $currencyData = array();
            if (!empty($currencyRates)) {
                //Organise data into array for bulk insert
                foreach ($currencyRates['Valute'] as $currencyRate) {
                    $currencyData[] = [
                        'valute_id' => $currencyRate['@attributes']['ID'],
                        'num_code' => $currencyRate['NumCode'],
                        'char_code' => $currencyRate['CharCode'],
                        'nominal' => $currencyRate['Nominal'],
                        'name' => $currencyRate['Name'],
                        'value' => $currencyRate['Value'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }

            if (!empty($currencyData)) {
                //Truncate ImportCurrencyRate table before insert
                ImportCurrencyRate::query()->truncate();
                //Insert bulk data
                ImportCurrencyRate::insert($currencyData);
                //To display success message
                $this->info('Currency rate inserted succcessfully.');
                //Save log
                Log::info('Currency rate inserted succcessfully. Details: ' . json_encode($currencyData));
            } else {
                $this->info('No Currency rate found for insert.');
            }
        } catch (Exception $e) {
            //Return error
            //To display an error message
            $this->error('Currency rate insertion error: ' . $e->getMessage);
            //Save log
            Log::error('Currency rate insertion error: ' . json_encode($e));
            return;
        }
    }
}
