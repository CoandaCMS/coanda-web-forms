<?php namespace CoandaCMS\CoandaWebForms\Artisan;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use CoandaCMS\CoandaWebForms\Exceptions\WebFormNotFoundException;
use League\Csv\Writer as CsvWriter;
use Coanda;

class GenerateSubmissionsCsv extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coanda-web-forms:generatesubmissioncsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a CSV file with all the submissions in it!';

    /**
     * @var
     */
    private $webformRepo;

    private $exporter;

    /**
     * @param $app
     */
    public function __construct($app)
    {
        $this->webformRepo = $app->make('CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface');
        $this->exporter = $app->make('CoandaCMS\CoandaWebForms\Exporters\CsvExporter');

        parent::__construct();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['form_id', InputArgument::REQUIRED, 'The form id to export.'],
            ['file_name', InputArgument::OPTIONAL, 'The name of the file you would like the generate.']
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['form_id', null, InputOption::VALUE_REQUIRED, 'The form is to export.', null],
            ['file_name', null, InputOption::VALUE_OPTIONAL, 'The name of the file to generate.', null]
        ];
    }

    /**
     * Run the command
     */
    public function fire()
    {
        $form_id = $this->argument('form_id');
        $file_name = $this->argument('file_name');

        try
        {
            $form = $this->webformRepo->getForm($form_id);

            $this->exporter->exportToFile($form, $file_name);
        }
        catch (WebFormNotFoundException $exception)
        {
            $this->error('Form #' . $form_id . ' not found, sorry.');
            return;
        }

        $this->info('Generated!' . $file_name);
    }
}