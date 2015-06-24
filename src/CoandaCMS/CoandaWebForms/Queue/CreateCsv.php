<?php namespace CoandaCMS\CoandaWebForms\Queue;

class CreateCsv {

    /**
     * @var 
     */
    protected $job;

    /**
     * @var \CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface
     */
    protected $formsRepository;

    /**
     * @var \CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormDownload
     */
    protected $download;

    /**
     * @var  \CoandaCMS\CoandaWebForms\Exporters\CsvExporter $exporter
     */
    protected $exporter;

    /**
     * __construct
     * 
     * @param \CoandaCMS\CoandaWebForms\Exporters\CsvExporter $exporter
     */
    public function __construct(\CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface $formsRepository, \CoandaCMS\CoandaWebForms\Exporters\CsvExporter $exporter) {
        $this->formsRepository = $formsRepository;
        $this->exporter = $exporter;
    }

    /**
     * Execute the queue job.
     *
     * @return void
     */
    public function fire($job, $data) {
        $this->job = $job;
        $this->download = $this->formsRepository->getDownload($data['id']);

        if ($this->download) {
            $this->run();
        }

        $this->job->delete();
    }

    /**
     * Run this job
     * 
     * @return void
     */
    protected function run() {
        $this->setStatusToInProgress()->createCsvFile();
    }

    /**
     * Sets the status of the WebFormDownload to in progress.
     *
     * @return CreateCsv $this
     */
    protected function setStatusToInProgress() {
        $this->download->status = 1;
        $this->download->save();

        return $this;
    }

    /**
     * Sets the status of the WebFormDownload to complete
     * and adds the CSV's filename.
     * 
     * @param  string $file_name
     */
    protected function setStatusToComplete($file_name) {
        $this->download->filename = $file_name;
        $this->download->status = 2;
        $this->download->save();

        return $this;
    }

    /**
     * Sets the status to failed. 
     *
     * @return 
     */
    protected function setStatusToFailed() {
        $this->download->status = -1;
        $this->download->save();

        return $this;
    }

    /**
     * Creates the CSV file using the CsvExporter
     * 
     * @return
     */
    protected function createCsvFile() {
        $form = $this->download->form;
        $file_name = $this->exporter->exportToFile($form, false, [], $this->download->id);

        if ($file_name) {
            $this->setStatusToComplete($file_name);

            return;
        }

        $this->setStatusToFailed();

        return;
    }

}