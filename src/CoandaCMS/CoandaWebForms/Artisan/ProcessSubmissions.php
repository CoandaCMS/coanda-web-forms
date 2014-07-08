<?php namespace CoandaCMS\CoandaWebForms\Artisan;

use Illuminate\Console\Command;
use Coanda;

class ProcessSubmissions extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coanda-web-forms:processsubmissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes the post submit handlers for submissions.';

    /**
     * @var
     */
    private $webformRepo;

    /**
     * @param $app
     */
    public function __construct($app)
    {
        $this->webformRepo = $app->make('CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface');

        parent::__construct();
    }

    /**
     * Run the command
     */
    public function fire()
    {
        $limit = 50;

        while(true)
        {
            $submissions = $this->webformRepo->getUnprocessesSubmissions($limit);

            if ($submissions->count() == 0)
            {
                $this->info('All done.');
                return;
            }

            foreach ($submissions as $submission)
            {
                $submission->form->executePostSubmissionHandlers($submission);
            }
        }
    }
}